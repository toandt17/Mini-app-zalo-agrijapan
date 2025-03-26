import React, { useState, useEffect, useRef } from "react";
import { useAtom } from "jotai";
import { luckyWheelState, luckyWheelDataState, gameHistoryState } from "@/state";
import type { Reward } from "@/types.d";
import { Button, Spinner } from "zmp-ui";
import confetti from "canvas-confetti";
import { getUserSpinTickets, useSpinTicket, saveUser } from "@/api/gameApi";
import { getUserInfo } from "zmp-sdk";
import toast from "react-hot-toast";
import "./lucky-wheel.css"; // Đảm bảo tạo file CSS này sau

// Hiệu ứng trúng thưởng 🎉
const triggerConfetti = () => {
  // Bắn pháo hoa một lần duy nhất với hiệu ứng lung linh
  confetti({
    particleCount: 180,
    spread: 120,
    origin: { y: 0.6, x: 0.5 },
    colors: ['#FFD700', '#FF4500', '#00BFFF', '#7CFC00', '#FF1493', '#9400D3', '#FF8C00', '#4682B4'],
    startVelocity: 45,
    gravity: 0.8,
    scalar: 1.2,
    ticks: 150,
    shapes: ['circle', 'square'],
    zIndex: 1000,
    disableForReducedMotion: true,
    drift: 0.5,
  });
  
  // Thêm hiệu ứng giãn từ hai bên để tạo hình vòng cung đẹp mắt
  confetti({
    particleCount: 60,
    angle: 60,
    spread: 60,
    origin: { x: 0, y: 0.65 },
    colors: ['#FFD700', '#FF4500', '#00BFFF', '#FF1493'],
    startVelocity: 35,
    gravity: 0.8,
    scalar: 1.1,
    drift: 1,
    ticks: 150
  });
  
  confetti({
    particleCount: 60,
    angle: 120,
    spread: 60,
    origin: { x: 1, y: 0.65 },
    colors: ['#7CFC00', '#9400D3', '#FF8C00', '#4682B4'],
    startVelocity: 35,
    gravity: 0.8,
    scalar: 1.1,
    drift: 1,
    ticks: 150
  });
};

// Toast notification function
const showToast = (message: string, type: 'success' | 'error' = 'success') => {
  if (type === 'success') {
    toast.success(message);
  } else {
    toast.error(message);
  }
};

// Lưu trữ ID người dùng từ database
interface UserData {
  id: number;
  zaloId: string | number;
  name?: string;
}

export default function LuckyWheelPage() {
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  const [luckyWheelData] = useAtom(luckyWheelDataState);
  const [gameHistory, setGameHistory] = useAtom(gameHistoryState);
  const [isSpinning, setIsSpinning] = useState(false);
  const [result, setResult] = useState<Reward | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);
  const [userData, setUserData] = useState<UserData | null>(null);
  const [userSaved, setUserSaved] = useState(false);
  const wheelRef = useRef<SVGSVGElement>(null);
  const [animationKey, setAnimationKey] = useState(0);
  const [activeBulbIndex, setActiveBulbIndex] = useState(-1);
  const bulbCount = 18; // Số lượng bóng đèn

  const colors = ["#FF6B6B", "#4ECDC4", "#FFD166", "#6A0572", "#F72585", "#4CC9F0", "#8338EC", "#FB5607"];
  
  // Load thông tin người dùng từ SDK Zalo
  useEffect(() => {
    async function fetchUserInfo() {
      try {
        // Check if running in dev mode (browser)
        const isDev = !window.ZJSBridge;
        
        if (isDev) {
          // Dev mode - use test ID
          console.log("Running in dev mode, using test user_id");
          setUserData({ id: 1, zaloId: "dev_user", name: "Test User" });
          setUserSaved(true); // Giả định user 1 đã được lưu trong dev mode
        } else {
          // Production - get actual Zalo user info
          const result = await getUserInfo({});
          console.log("Zalo user info:", result);
          
          if (result && result.userInfo && result.userInfo.id) {
            const zaloId = result.userInfo.id;
            
            // Lưu thông tin người dùng vào system
            const saveResult = await saveUser({
              zaloId: zaloId,
              name: result.userInfo.name || "",
              avatar: result.userInfo.avatar || "",
              idByOA: result.userInfo.idByOA || "", 
              followedOA: result.userInfo.followedOA || false,
              isSensitive: result.userInfo.isSensitive || false
            });
            
            if (saveResult.success && saveResult.user) {
              console.log("User saved successfully:", saveResult);
              // Lưu database ID từ response API
              setUserData({
                id: saveResult.user.id,
                zaloId: zaloId,
                name: saveResult.user.name
              });
              setUserSaved(true);
              
              // Hiển thị toast chào mừng nếu là user mới
              if (saveResult.is_new) {
                toast.success(`Chào mừng ${saveResult.user.name || "bạn"} đến với ứng dụng!`);
              }
            } else {
              console.error("Error saving user:", saveResult);
              toast.error("Không thể lưu thông tin người dùng");
            }
          } else {
            toast.error("Không thể lấy thông tin người dùng");
            console.error("Không thể lấy Zalo ID");
          }
        }
      } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
        toast.error("Không thể lấy thông tin người dùng");
      }
    }
    
    fetchUserInfo();
  }, []);
  
  // Load dữ liệu vòng quay khi component được mount và khi userData thay đổi
  useEffect(() => {
    if (userData && userSaved) {
      loadWheelData();
    }
  }, [userData, userSaved]);
  
  // Tải dữ liệu vòng quay
  const loadWheelData = async () => {
    if (!userData || !userSaved) return;
    
    try {
      setLoading(true);
      // Tải dữ liệu vòng quay
      const data = await luckyWheelData;
      if (data && data.rewards.length > 0) {
        setLuckyWheel(data);
      }
      
      // Lấy số lượt quay còn lại từ API
      console.log("Getting spin tickets for user ID:", userData.id);
      const spinTickets = await getUserSpinTickets(userData.id);
      if (spinTickets && spinTickets.success) {
        setLuckyWheel(prev => ({
          ...prev,
          remainingSpins: spinTickets.data.spin_count || 0
        }));
      } else {
        console.error("Error fetching spin tickets:", spinTickets);
      }
    } catch (error) {
      console.error("Error loading wheel data:", error);
      setError("Không thể tải dữ liệu vòng quay");
    } finally {
      setLoading(false);
    }
  };
  
  // Hàm thử lưu lại thông tin user khi gặp lỗi
  const retryUserSave = async () => {
    try {
      // Lấy lại thông tin từ Zalo SDK
      const result = await getUserInfo({});
      
      if (result && result.userInfo && result.userInfo.id) {
        // Lưu thông tin người dùng vào system
        const saveResult = await saveUser({
          zaloId: result.userInfo.id,
          name: result.userInfo.name || "",
          avatar: result.userInfo.avatar || "",
          idByOA: result.userInfo.idByOA || "",
          followedOA: result.userInfo.followedOA || false,
          isSensitive: result.userInfo.isSensitive || false
        });
        
        if (saveResult.success && saveResult.user) {
          console.log("User re-saved successfully:", saveResult);
          // Cập nhật userData với database ID
          setUserData({
            id: saveResult.user.id,
            zaloId: result.userInfo.id,
            name: saveResult.user.name
          });
          setUserSaved(true);
          return true;
        }
      }
      
      return false;
    } catch (error) {
      console.error("Error retrying user save:", error);
      return false;
    }
  };

  const startSpin = async () => {
    if (isSpinning || luckyWheel.remainingSpins <= 0 || luckyWheel.rewards.length === 0 || !userData || !userSaved) {
      if (!userData) {
        toast.error('Không thể xác định thông tin người dùng');
      } else if (!userSaved) {
        toast.error('Đang lưu thông tin người dùng, vui lòng đợi chút');
      } else {
        showToast(luckyWheel.remainingSpins <= 0 ? 'Bạn đã hết lượt quay' : 'Không thể quay lúc này', 'error');
      }
      return;
    }

    try {
      // Hiển thị trạng thái đang lấy kết quả (không phải đang quay)
      const spinButton = document.getElementById('spin-button') as HTMLButtonElement;
      if (spinButton) {
        spinButton.textContent = 'Đang lấy kết quả...';
        spinButton.disabled = true;
      }

      // Lấy kết quả từ API trước khi bắt đầu quay
      const spinResult = await useSpinTicket(userData.id, 0);
      
      if (!spinResult || !spinResult.success) {
        // Nếu lỗi "User not found", thử lại việc lưu user
        if (spinResult?.message && spinResult.message.includes("No query results for model")) {
          showToast('Tài khoản chưa được nhận diện, đang thử lại...', 'error');
          const retrySave = await retryUserSave();
          if (retrySave) {
            showToast('Đã cập nhật thông tin, vui lòng thử quay lại', 'success');
            // Reset nút quay
            if (spinButton) {
              spinButton.textContent = 'Quay ngay';
              spinButton.disabled = false;
            }
            return;
          }
        }
        throw new Error(spinResult?.message || 'Lỗi khi quay thưởng');
      }
      
      // Tìm giải thưởng trong danh sách
      const prizeId = spinResult.data.prize_id;
      const rewardIndex = luckyWheel.rewards.findIndex(r => r.id === prizeId);
      
      if (rewardIndex === -1) {
        throw new Error('Không tìm thấy giải thưởng phù hợp');
      }

      console.log("Đã có kết quả từ API. Kết quả:", spinResult.data);

      // Sau khi có kết quả, bắt đầu animation
      setIsSpinning(true);
      setResult(null);

      // Bắt đầu hiệu ứng đèn chạy
      startBulbRotationEffect();

      // Cập nhật lại trạng thái nút
      if (spinButton) {
        spinButton.textContent = 'Đang quay...';
      }

      const selectedReward = luckyWheel.rewards[rewardIndex];
      
      // Tính góc quay đến phần thưởng
      const totalSlices = luckyWheel.rewards.length;
      const degreesPerSlice = 360 / totalSlices;
      const targetDegree = rewardIndex * degreesPerSlice;
      const finalAngle = 360 - targetDegree - (degreesPerSlice / 2);
      
      // Tạo style animation mới
      const styleSheet = document.createElement("style");
      styleSheet.id = "wheel-animation-style";
      const existingStyle = document.getElementById("wheel-animation-style");
      if (existingStyle) {
        document.head.removeChild(existingStyle);
      }
      
      // Tạo keyframes chỉ với 2 điểm: bắt đầu và kết thúc để tránh khựng giữa chừng
      const keyframes = `
        @keyframes spin-wheel-${animationKey} {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(${1440 + finalAngle}deg); }
        }
      `;
      
      styleSheet.textContent = keyframes;
      document.head.appendChild(styleSheet);
      
      // Áp dụng animation với timing function tự nhiên
      if (wheelRef.current) {
        wheelRef.current.style.animation = `spin-wheel-${animationKey} 5s cubic-bezier(0.34, 0.52, 0.07, 0.99) forwards`;
        // Thêm class cho hiệu ứng khi quay
        wheelRef.current.parentElement?.classList.add('wheel-spinning');
      }
      
      // Tăng animation key để tạo animation mới cho lần quay tiếp theo
      setAnimationKey(prev => prev + 1);
      
      // Sau khi quay xong
      setTimeout(() => {
        setIsSpinning(false);
        setResult(selectedReward);
        setLuckyWheel({
          ...luckyWheel,
          remainingSpins: spinResult.data.remaining_spins
        });
        
        setGameHistory([
          { 
            id: Date.now(), 
            type: "spin", 
            result: selectedReward.name,
            createdAt: new Date()
          }, 
          ...gameHistory
        ]);
        
        // Reset nút quay về trạng thái ban đầu
        const spinButton = document.getElementById('spin-button') as HTMLButtonElement;
        if (spinButton) {
          spinButton.textContent = spinResult.data.remaining_spins <= 0 ? 'Hết lượt' : 'Quay ngay';
          spinButton.disabled = spinResult.data.remaining_spins <= 0;
        }
        
        if (spinResult.data.has_reward) {
          triggerConfetti();
          showToast(`Chúc mừng! Bạn đã nhận được: ${selectedReward.name}`, 'success');
          
          // Thêm hiệu ứng khi trúng thưởng
          const resultElement = document.getElementById('result-container');
          if (resultElement) {
            resultElement.classList.add('prize-won');
          }
        }
      }, 5000);
      
      // Loại bỏ class hiệu ứng sau khi quay xong
      setTimeout(() => {
        if (wheelRef.current) {
          wheelRef.current.parentElement?.classList.remove('wheel-spinning');
        }
      }, 5200);
    } catch (err: any) {
      console.error('Lỗi khi quay thưởng:', err);
      setIsSpinning(false);
      
      // Reset nút khi có lỗi
      const spinButton = document.getElementById('spin-button') as HTMLButtonElement;
      if (spinButton) {
        spinButton.textContent = 'Quay ngay';
        spinButton.disabled = false;
      }
      
      // Xử lý lỗi 500 chi tiết hơn
      if (err.response && err.response.status === 500) {
        // Kiểm tra nếu là lỗi user not found
        if (err.response.data && err.response.data.message && 
            err.response.data.message.includes("No query results for model")) {
          toast.error('Tài khoản chưa được nhận diện. Hệ thống sẽ thử đăng ký lại thông tin tài khoản.');
          
          // Thử lưu lại thông tin user
          const retrySave = await retryUserSave();
          if (retrySave) {
            toast.success('Đã cập nhật thông tin, vui lòng thử quay lại');
          }
        } else {
          toast.error('Lỗi máy chủ: Có thể bạn đã quay rồi hoặc server đang bảo trì');
        }
      } else {
        showToast(err.message || 'Không thể quay thưởng, vui lòng thử lại sau', 'error');
      }
    }
  };

  // Hàm tạo hiệu ứng đèn chạy
  const startBulbRotationEffect = () => {
    // Reset active bulb
    setActiveBulbIndex(-1);
    
    // Tạo interval để đèn chạy
    const interval = setInterval(() => {
      setActiveBulbIndex(prevIndex => {
        // Nếu prevIndex = -1, bắt đầu từ đèn đầu tiên
        if (prevIndex === -1) return 0;
        // Nếu đã đến đèn cuối, quay lại đèn đầu
        if (prevIndex === bulbCount - 1) return 0;
        // Tiếp tục đến đèn tiếp theo
        return prevIndex + 1;
      });
    }, 100); // Tốc độ chạy đèn, điều chỉnh để phù hợp

    // Dừng hiệu ứng sau 5 giây (khi vòng quay dừng)
    setTimeout(() => {
      clearInterval(interval);
      setActiveBulbIndex(-1); // Reset lại trạng thái đèn
    }, 5000);
  };
  
  const splitText = (text: string, maxWordsPerLine = 2) => {
    const words = text.split(" ");
    const lines: string[] = [];
    for (let i = 0; i < words.length; i += maxWordsPerLine) {
      lines.push(words.slice(i, i + maxWordsPerLine).join(" "));
    }
    return lines;
  };
  
  const generateWheelSegments = () => {
    if (!luckyWheel.rewards || luckyWheel.rewards.length === 0) return null;
    
    const totalRewards = luckyWheel.rewards.length;
    const segmentAngle = 360 / totalRewards;
    const radius = 180; // Tăng bán kính để vòng quay lớn hơn
    const centerX = 200;
    const centerY = 200;
    
    // Tính toán mức độ co lại của văn bản dựa trên số phần thưởng
    const textCompression = Math.min(1, 8 / totalRewards);
    const textRadius = radius * 0.65; // Điều chỉnh vị trí văn bản
  
    return luckyWheel.rewards.map((reward, i) => {
      // Tạo một mảng màu đẹp mắt, xen kẽ các màu sáng tối cho các phân đoạn vòng quay
      const wheelColors = [
        "#f44336", "#ef5350", "#e53935", "#d32f2f", // Các sắc thái đỏ
        "#ff5722", "#ff7043", "#f4511e", "#e64a19", // Các sắc thái cam đỏ
        "#ffeb3b", "#ffee58", "#fdd835", "#fbc02d", // Các sắc thái vàng
        "#f44336", "#ef5350", "#e53935", "#d32f2f", // Lặp lại các sắc thái đỏ
      ];
      
      const color = reward.color || wheelColors[i % wheelColors.length];
      const startAngle = i * segmentAngle;
      const endAngle = (i + 1) * segmentAngle;
      const startRad = ((startAngle - 90) * Math.PI) / 180;
      const endRad = ((endAngle - 90) * Math.PI) / 180;
      const x1 = centerX + radius * Math.cos(startRad);
      const y1 = centerY + radius * Math.sin(startRad);
      const x2 = centerX + radius * Math.cos(endRad);
      const y2 = centerY + radius * Math.sin(endRad);
      const path = `M ${centerX} ${centerY} L ${x1} ${y1} A ${radius} ${radius} 0 0 1 ${x2} ${y2} Z`;
  
      // Màu gradient nội suy giữa hai màu
      const gradientId = `gradient-${i}`;
      // Tạo màu sáng hơn cho phần đầu gradient
      const lighterColor = color.replace(/[0-9a-f]{6}/i, match => {
        let r = parseInt(match.substring(0, 2), 16);
        let g = parseInt(match.substring(2, 4), 16);
        let b = parseInt(match.substring(4, 6), 16);
        r = Math.min(255, r + 40);
        g = Math.min(255, g + 40);
        b = Math.min(255, b + 40);
        return `${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
      });

      // Hoa văn trang trí cho các phân đoạn
      const pattern = (i % 2 === 0) ? 
        'url(#pattern-stripes)' : 
        'url(#pattern-dots)';
      
      // Tính toán góc giữa cho văn bản
      const midRad = (startRad + endRad) / 2;
      const midAngle = ((startAngle + endAngle) / 2);
      const textX = centerX + textRadius * Math.cos(midRad);
      const textY = centerY + textRadius * Math.sin(midRad);
      
      // Xử lý văn bản dựa trên vị trí để đảm bảo luôn đọc được
      let textAngle = 0;
      
      // Điều chỉnh góc văn bản dựa trên vị trí trong vòng tròn
      if (midAngle <= 90 || midAngle > 270) {
        // Nửa dưới bên phải và nửa trên bên phải
        textAngle = midAngle;
      } else {
        // Nửa trên bên trái và nửa dưới bên trái
        textAngle = midAngle - 180;
      }
      
      // Xử lý văn bản làm sạch
      let cleanText = reward.name.trim();
      // Loại bỏ các ký tự đặc biệt không mong muốn
      cleanText = cleanText.replace(/[^\p{L}\p{N}\p{P}\p{Z}]/gu, '');
      
      // Tính toán kích thước font dựa trên độ dài của văn bản
      const fontSize = Math.max(10, Math.min(13, 20 * textCompression - (cleanText.length > 15 ? 2 : 0)));
      
      return (
        <g key={reward.id}>
          {/* Định nghĩa gradient */}
          <defs>
            <radialGradient id={gradientId} cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
              <stop offset="0%" stopColor={lighterColor} />
              <stop offset="100%" stopColor={color} />
            </radialGradient>
            
            {/* Pattern cho các sọc */}
            <pattern id="pattern-stripes" patternUnits="userSpaceOnUse" width="8" height="8" patternTransform="rotate(45)">
              <rect width="4" height="8" fill="rgba(255, 255, 255, 0.1)" />
            </pattern>
            
            {/* Pattern cho các chấm */}
            <pattern id="pattern-dots" patternUnits="userSpaceOnUse" width="10" height="10">
              <circle cx="5" cy="5" r="1.5" fill="rgba(255, 255, 255, 0.15)" />
            </pattern>
          </defs>
          
          {/* Vẽ phần của bánh xe với gradient */}
          <path 
            d={path} 
            fill={`url(#${gradientId})`} 
            stroke="white" 
            strokeWidth="2" 
            strokeLinejoin="round"
          />
          
          {/* Thêm hoa văn trang trí */}
          <path 
            d={path} 
            fill={pattern} 
            opacity="0.6"
          />
          
          {/* Đường viền sáng */}
          <path 
            d={path} 
            fill="none" 
            stroke="rgba(255,255,255,0.7)" 
            strokeWidth="1" 
            strokeDasharray="3,3"
            opacity="0.7"
          />
          
          {/* Hiển thị văn bản - cải tiến để không bị tách chữ */}
          <g transform={`rotate(${textAngle}, ${textX}, ${textY})`}>
            {cleanText.length > 10 ? (
              // Tìm vị trí khoảng trắng gần giữa nhất để chia cụm từ có nghĩa
              (() => {
                // Tìm điểm chia hợp lý nhất (khoảng trắng gần giữa nhất)
                const midPoint = Math.floor(cleanText.length / 2);
                let breakPoint = -1;
                
                // Tìm khoảng trắng gần giữa nhất để chia văn bản
                if (cleanText.includes(' ')) {
                  let minDistance = cleanText.length;
                  for (let i = 0; i < cleanText.length; i++) {
                    if (cleanText[i] === ' ') {
                      const distance = Math.abs(i - midPoint);
                      if (distance < minDistance) {
                        minDistance = distance;
                        breakPoint = i;
                      }
                    }
                  }
                }
                
                // Nếu không tìm thấy khoảng trắng hoặc là một từ dài, chia theo kích thước
                if (breakPoint === -1) {
                  breakPoint = midPoint;
                }
                
                const firstPart = cleanText.substring(0, breakPoint);
                const secondPart = cleanText.substring(breakPoint).trim();
                
                return (
                  <>
                    <text
                      x={textX}
                      y={textY - 8}
                      fill="white"
                      fontSize={`${fontSize}px`}
                      fontWeight="bold"
                      textAnchor="middle"
                      dominantBaseline="middle"
                      style={{
                        textShadow: '0px 1px 2px rgba(0,0,0,0.8)'
                      }}
                    >
                      {firstPart}
                    </text>
                    <text
                      x={textX}
                      y={textY + 8}
                      fill="white"
                      fontSize={`${fontSize}px`}
                      fontWeight="bold"
                      textAnchor="middle"
                      dominantBaseline="middle"
                      style={{
                        textShadow: '0px 1px 2px rgba(0,0,0,0.8)'
                      }}
                    >
                      {secondPart}
                    </text>
                  </>
                );
              })()
            ) : (
              // Nếu văn bản ngắn, hiển thị trên một dòng
              <text
                x={textX}
                y={textY}
                fill="white"
                fontSize={`${fontSize}px`}
                fontWeight="bold"
                textAnchor="middle"
                dominantBaseline="middle"
                style={{
                  textShadow: '0px 1px 2px rgba(0,0,0,0.8)'
                }}
              >
                {cleanText}
              </text>
            )}
          </g>
        </g>
      );
    });
  };

  const generateBulbs = (count = 18, radius = 188): React.ReactNode[] => {
    const bulbs: React.ReactNode[] = [];
    const centerX = 200;
    const centerY = 200;

    for (let i = 0; i < count; i++) {
      const angle = (i / count) * 2 * Math.PI;
      const x = centerX + radius * Math.cos(angle);
      const y = centerY + radius * Math.sin(angle);
      
      // Kiểm tra xem đèn này có đang ở trạng thái active không
      const isActive = i === activeBulbIndex;
      
      bulbs.push(
        <g key={`bulb-${i}`} className="wheel-bulb" transform={`translate(${x}, ${y})`}>
          {/* Tạo bóng đèn có ánh sáng tĩnh không nhấp nháy, sáng hơn nếu là active */}
          <circle 
            cx="0" 
            cy="0" 
            r="5" 
            fill={isActive ? "#FFFFFF" : "#FFD700"} 
            className="bulb-glow"
            style={{
              filter: isActive ? 'drop-shadow(0 0 5px #FFFFFF)' : '',
              opacity: isActive ? '1' : '0.65'
            }}
          />
          <circle 
            cx="0" 
            cy="0" 
            r="3" 
            fill="#FFFFFF" 
            opacity={isActive ? "0.9" : "0.7"} 
          />
          <circle 
            cx="0" 
            cy="0" 
            r="1" 
            fill="#FFFFFF" 
            opacity={isActive ? "1" : "0.9"} 
          />
        </g>
      );
    }
    return bulbs;
  };

  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center p-4 h-full">
        <Spinner />
        <p className="mt-4">
          {!userSaved ? 'Đang xác thực thông tin người dùng...' : 'Đang tải dữ liệu vòng quay...'}
        </p>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex flex-col items-center justify-center p-4 h-full">
        <h2 className="text-xl font-bold text-red-600 mb-2">Lỗi</h2>
        <p>{error}</p>
        <Button className="mt-4" onClick={() => window.location.reload()}>Thử lại</Button>
      </div>
    );
  }

  return (
    <div className="flex flex-col items-center p-4 h-full bg-gradient-to-b from-red-50 to-red-100">
      <h2 className="text-2xl font-bold text-red-800 mb-3 text-center drop-shadow-sm">🎡 Vòng Quay May Mắn</h2>
      
      {userData && userData.name && (
        <div className="bg-white rounded-xl p-3 shadow-md mb-4 text-center w-full max-w-md">
          <p className="text-sm text-gray-600">
            Xin chào, <span className="font-medium text-red-700">{userData.name}</span>
          </p>
        </div>
      )}
      
      <p className="text-red-600 mb-2 bg-white px-4 py-2 rounded-full shadow-sm">
        Bạn còn <span className="font-bold text-xl text-red-800">{luckyWheel.remainingSpins}</span> lượt quay
      </p>
      
      <div className="relative w-full max-w-md mx-auto my-4 wheel-container">
        {/* Mũi tên chỉ báo */}
        <div className="wheel-pointer">
          <div className="w-0 h-0 border-l-[20px] border-r-[20px] border-t-[32px] border-l-transparent border-r-transparent border-t-red-600 drop-shadow-lg filter"></div>
        </div>
        
        {/* Khung vòng quay */}
        <div className="relative w-full aspect-square rounded-full overflow-hidden shadow-lg wheel-border bg-red-800">
          <svg
            ref={wheelRef}
            viewBox="0 0 400 400"
            className="w-full h-full wheel-svg"
            style={{ filter: 'drop-shadow(0px 5px 10px rgba(0, 0, 0, 0.25))' }}
          >
            <defs>
              <filter id="text-shadow" x="-20%" y="-20%" width="140%" height="140%">
                <feDropShadow dx="0" dy="0" stdDeviation="1" floodColor="#000" floodOpacity="0.7" />
              </filter>
              <radialGradient id="center-gradient" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
                <stop offset="0%" stopColor="#f87171" />
                <stop offset="100%" stopColor="#991b1b" />
              </radialGradient>
            </defs>
            
            {/* Các phân khúc vòng quay */}
            <g>{generateWheelSegments()}</g>
            
            {/* Thêm các bóng đèn xung quanh */}
            <g>{generateBulbs()}</g>
          </svg>
          
          {/* Nút "QUAY" ở giữa tách riêng thành phần absolute để không quay theo */}
          <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[90px] h-[90px] rounded-full flex items-center justify-center"
              style={{ 
                background: 'radial-gradient(circle, #f87171 0%, #991b1b 100%)',
                boxShadow: '0 0 0 3px white',
                pointerEvents: 'none',
                zIndex: 5
              }}
          >
            <span className="text-white font-bold text-xl" style={{textShadow: '1px 1px 3px #000'}}>QUAY</span>
          </div>
          
          {/* Nút ấn quay ở giữa */}
          <div 
            className="absolute top-[50%] left-[50%] transform -translate-x-1/2 -translate-y-1/2 w-[95px] h-[95px] rounded-full bg-red-500 opacity-0 cursor-pointer hover:opacity-15 transition-opacity duration-300 z-10"
            onClick={startSpin}
          />
          
          {luckyWheel.remainingSpins <= 0 && (
            <div className="absolute bottom-4 left-0 right-0 text-center">
              <p className="text-red-100 font-bold mt-2 bg-red-900/80 rounded-lg mx-auto inline-block px-4 py-1 shadow-md">Bạn đã hết lượt quay!</p>
            </div>
          )}
        </div>
      </div>
      
      {result && (
        <div 
          id="result-container"
          className="p-5 mb-6 rounded-xl text-center bg-gradient-to-r from-red-50 via-yellow-50 to-red-50 shadow-md border border-red-200 w-full max-w-md animate-fadeIn"
        >
          <div className="flex flex-col items-center">
            <div className="w-12 h-12 rounded-full bg-red-400 mb-2 flex items-center justify-center">
              <span className="text-2xl">🎊</span>
            </div>
            <h3 className="text-xl font-bold text-red-800 mb-2">Chúc mừng bạn!</h3>
            <p className="text-red-700 text-xl font-bold">{result.name}</p>
          </div>
        </div>
      )}
      
      <Button 
        id="spin-button"
        className={`w-44 h-14 rounded-full text-lg font-bold shadow-md transition-all duration-300 
          ${isSpinning || luckyWheel.remainingSpins <= 0 || !userData || !userSaved
            ? "bg-gray-300 text-gray-500" 
            : "bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white transform hover:scale-105"}`} 
        disabled={isSpinning || luckyWheel.remainingSpins <= 0 || !userData || !userSaved} 
        onClick={startSpin}
      >
        {isSpinning ? "Đang quay..." : 
          !userSaved ? "Đang cập nhật thông tin..." :
          luckyWheel.remainingSpins <= 0 ? "Hết lượt" : "Quay ngay"}
      </Button>
      
      <div className="mt-6 text-center w-full max-w-md bg-white rounded-xl shadow-md p-4">
        <h3 className="text-lg font-bold text-red-800 mb-2">🎁 Cách nhận thêm lượt quay</h3>
        <p className="text-red-600 mb-3">Điểm danh hằng ngày để nhận thêm lượt quay miễn phí!</p>
        <div className="flex justify-center gap-4 flex-wrap">
          <Button
            className="w-40 h-12 rounded-full bg-gradient-to-r from-red-400 to-red-600 text-white font-bold shadow-sm hover:shadow-md transition-all duration-300"
            onClick={() => window.location.href = "/games/check-in"}
          >
            Điểm danh ngay
          </Button>
          <Button
            className="w-40 h-12 rounded-full bg-gradient-to-r from-amber-500 to-amber-700 text-white font-bold shadow-sm hover:shadow-md transition-all duration-300"
            onClick={() => window.location.href = "/games/tasks"}
          >
            Làm nhiệm vụ
          </Button>
        </div>
      </div>
    </div>
  );
}
