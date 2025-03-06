import React, { useState, useRef, useEffect } from 'react';
import { useAtom } from 'jotai';
import { luckyWheelState, gameHistoryState } from '@/state';
import { Button, Icon } from 'zmp-ui';
import { Reward } from '@/types';

// Đơn giản hóa confetti
const triggerConfetti = () => {
  console.log('Hiệu ứng confetti kích hoạt');
  // Implement hiệu ứng đơn giản nếu cần
};

export default function LuckyWheelPage() {
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  const [gameHistory, setGameHistory] = useAtom(gameHistoryState);
  const [isSpinning, setIsSpinning] = useState(false);
  const [result, setResult] = useState<Reward | null>(null);
  const [rotation, setRotation] = useState(0);
  
  const startSpin = () => {
    if (isSpinning || luckyWheel.remainingSpins <= 0) return;
    
    setIsSpinning(true);
    setResult(null);
    
    // Random select a reward
    const randomIndex = Math.floor(Math.random() * luckyWheel.rewards.length);
    const selectedReward = luckyWheel.rewards[randomIndex];
    
    // Calculate rotation
    // Each segment is 360 / number of rewards degrees
    const segmentSize = 360 / luckyWheel.rewards.length;
    // Target rotation is opposite to the segment (so it lands on the indicator)
    const targetRotation = 360 * 5 + (360 - (randomIndex * segmentSize + segmentSize / 2));
    
    // Set rotation with animation
    setRotation(prevRotation => prevRotation + targetRotation);
    
    // Wait for animation to finish
    setTimeout(() => {
      setIsSpinning(false);
      setResult(selectedReward);
      
      // Update state
      setLuckyWheel({
        ...luckyWheel,
        remainingSpins: luckyWheel.remainingSpins - 1,
        lastSpin: new Date()
      });
      
      // Add to history
      setGameHistory([
        {
          id: Date.now(),
          type: 'spin',
          result: selectedReward.name,
          reward: selectedReward,
          createdAt: new Date()
        },
        ...gameHistory
      ]);
      
      // Trigger confetti if it's a real prize
      if (selectedReward.type !== 'none') {
        triggerConfetti();
      }
    }, 5000); // Match with CSS animation time
  };
  
  // Tạo các phân đoạn SVG cho vòng quay
  const generateWheelSegments = () => {
    const segments = [];
    const totalRewards = luckyWheel.rewards.length;
    const segmentAngle = 360 / totalRewards;
    const radius = 150; // Bán kính vòng tròn
    const centerX = 200; // Tọa độ tâm X
    const centerY = 200; // Tọa độ tâm Y
    
    for (let i = 0; i < totalRewards; i++) {
      const reward = luckyWheel.rewards[i];
      const startAngle = i * segmentAngle;
      const endAngle = (i + 1) * segmentAngle;
      
      // Convert from degrees to radians
      const startRad = (startAngle - 90) * Math.PI / 180;
      const endRad = (endAngle - 90) * Math.PI / 180;
      
      // Calculate the coordinates
      const x1 = centerX + radius * Math.cos(startRad);
      const y1 = centerY + radius * Math.sin(startRad);
      const x2 = centerX + radius * Math.cos(endRad);
      const y2 = centerY + radius * Math.sin(endRad);
      
      // Generate the SVG path
      const largeArcFlag = segmentAngle > 180 ? 1 : 0;
      const path = `M ${centerX} ${centerY} L ${x1} ${y1} A ${radius} ${radius} 0 ${largeArcFlag} 1 ${x2} ${y2} Z`;
      
      // Tính toán vị trí và hướng cho văn bản dọc
      const midAngle = startAngle + segmentAngle / 2;
      const midRad = (midAngle - 90) * Math.PI / 180;
      
      // Tạo nhiều điểm dọc theo đường thẳng từ tâm đến biên để đặt từng ký tự
      const characters = reward.name.split('');
      const textPaths = [];
      
      // Tính toán vị trí cho đường dẫn văn bản
      const textPathId = `textPath-${reward.id}`;
      const pathStartX = centerX + 55 * Math.cos(midRad); // Điểm bắt đầu ở gần tâm
      const pathStartY = centerY + 55 * Math.sin(midRad);
      const pathEndX = centerX + 135 * Math.cos(midRad); // Điểm kết thúc gần biên
      const pathEndY = centerY + 135 * Math.sin(midRad);
      
      segments.push(
        <g key={reward.id}>
          <path 
            d={path} 
            fill={reward.color} 
            stroke="#fff" 
            strokeWidth="1"
          />
          
          {/* Tạo đường dẫn ẩn để văn bản đi theo */}
          <path
            id={textPathId}
            d={`M ${pathStartX} ${pathStartY} L ${pathEndX} ${pathEndY}`}
            stroke="none"
            fill="none"
          />
          
          {/* Văn bản đi theo đường dẫn */}
          <text
            fill="white"
            fontWeight="bold"
            fontSize="12px"
            textAnchor="middle"
            dominantBaseline="middle"
          >
            <textPath 
              xlinkHref={`#${textPathId}`} 
              startOffset="50%"
              textAnchor="middle"
            >
              {reward.name}
            </textPath>
          </text>
        </g>
      );
    }
    
    return segments;
  };
  
  return (
    <div className="flex flex-col items-center p-4 h-full">
      <div className="bg-gradient-to-b from-indigo-100 to-purple-100 w-full rounded-xl p-4 mb-6 text-center">
        <h2 className="text-xl font-bold text-indigo-800 mb-2">Vòng Quay May Mắn</h2>
        <p className="text-indigo-600">Bạn còn <span className="font-bold text-xl">{luckyWheel.remainingSpins}</span> lượt quay</p>
      </div>
      
      {/* Result display */}
      {result && (
        <div className={`w-full p-4 mb-6 rounded-xl text-center ${
          result.type === 'none' ? 'bg-gray-100' : 'bg-yellow-100'
        }`}>
          <h3 className="text-lg font-bold mb-1">
            {result.type === 'none' ? 'Chúc bạn may mắn lần sau!' : 'Chúc mừng!'}
          </h3>
          <p className={`${result.type === 'none' ? 'text-gray-700' : 'text-yellow-700'} font-medium`}>
            {result.type === 'none' ? 'Hãy tiếp tục tham gia' : `Bạn đã nhận được: ${result.name}`}
          </p>
        </div>
      )}
      
      {/* Lucky Wheel - Sử dụng SVG */}
      <div className="relative w-full max-w-xs mx-auto mb-6">
        {/* Indicator (pointer) */}
        <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
          <div className="w-0 h-0 border-l-[15px] border-r-[15px] border-t-[25px] border-l-transparent border-r-transparent border-t-red-500"></div>
        </div>
        
        <div className="relative w-full aspect-square">
          <svg 
            viewBox="0 0 400 400" 
            className="w-full h-full" 
            style={{
              transform: `rotate(${rotation}deg)`,
              transition: "transform 5s cubic-bezier(0.17, 0.67, 0.14, 0.96)"
            }}
          >
            {/* Phân đoạn vòng quay */}
            <g>{generateWheelSegments()}</g>
            
            {/* Viền ngoài vòng quay */}
            <circle 
              cx="200" 
              cy="200" 
              r="150" 
              fill="none" 
              stroke="gold" 
              strokeWidth="5"
            />
            
            {/* Viền trong */}
            <circle 
              cx="200" 
              cy="200" 
              r="40" 
              fill="#2196F3" 
              stroke="white" 
              strokeWidth="2"
            />
            
            {/* Text ở giữa */}
            <text 
              x="200" 
              y="205" 
              textAnchor="middle" 
              fill="white" 
              fontWeight="bold"
              fontSize="16"
            >
              Quay
            </text>
          </svg>
        </div>
      </div>
      
      {/* Spin button */}
      <Button 
        className={`w-40 h-14 rounded-full text-lg font-bold shadow-md ${isSpinning || luckyWheel.remainingSpins <= 0 ? 'bg-gray-300' : 'bg-gradient-to-r from-indigo-500 to-purple-600'}`}
        disabled={isSpinning || luckyWheel.remainingSpins <= 0}
        onClick={startSpin}
      >
        {isSpinning ? 'Đang quay...' : luckyWheel.remainingSpins <= 0 ? 'Hết lượt' : 'Quay ngay'}
      </Button>
      
      {/* Info section */}
      <div className="mt-8 w-full bg-white rounded-xl p-4 shadow-md">
        <h3 className="font-bold text-lg mb-2 text-center">Cách nhận thêm lượt quay</h3>
        <ul className="space-y-2">
          <li className="flex items-center">
            <Icon icon="zi-calendar" className="mr-2 text-green-500" />
            <span>Điểm danh hàng ngày: +1 lượt</span>
          </li>
          <li className="flex items-center">
            <Icon icon="zi-user-add" className="mr-2 text-blue-500" />
            <span>Mời bạn bè tham gia: +2 lượt</span>
          </li>
          <li className="flex items-center">
            <Icon icon="zi-share" className="mr-2 text-purple-500" />
            <span>Chia sẻ lên trang cá nhân: +1 lượt</span>
          </li>
        </ul>
      </div>
    </div>
  );
} 