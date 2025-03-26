import { useAtom, useAtomValue } from "jotai";
import { activeMissionState, missionFilterState, missionsState, userState } from "@/state";
import { completeMission, getMissions, saveUser, markVideoWatched, markArticleAsRead, addArticleComment, verifyZaloFollow, verifyProfileCompletion, trackMissionProgress, getMissionProgress } from "@/api/gameApi";
import { useEffect, useState } from "react";
import { Icon } from "zmp-ui";
import toast from "react-hot-toast";
import { useNavigate } from "react-router-dom";
import { getUserInfo } from "zmp-sdk";

// Interface cho thông tin người dùng
interface UserData {
  id: number | string;
  zaloId: string | number;
  name?: string;
  avatar?: string;
}

function MissionPage() {
  const [missions, setMissions] = useAtom(missionsState);
  const [filter, setFilter] = useAtom(missionFilterState);
  const [activeMission, setActiveMission] = useAtom(activeMissionState);
  const [user, setUser] = useAtom(userState);
  const [loading, setLoading] = useState(false);
  const [loadingMission, setLoadingMission] = useState<number | null>(null);
  const [userData, setUserData] = useState<UserData | null>(null);
  const [userSaved, setUserSaved] = useState(false);
  const [showConfirmModal, setShowConfirmModal] = useState<number | null>(null);
  const [showRewardModal, setShowRewardModal] = useState(false);
  const [currentReward, setCurrentReward] = useState<{
    points?: number;
    spin_tickets?: number;
    name?: string;
  } | null>(null);
  const navigate = useNavigate();

  console.log("MissionPage - Component rendered", { user, userData, missions, filter });

  // Lấy thông tin người dùng từ Zalo SDK
  useEffect(() => {
    async function fetchUserInfo() {
      try {
        // Kiểm tra xem đang chạy trong môi trường dev (trình duyệt) hay không
        const isDev = !window.ZJSBridge;
        
        if (isDev) {
          // Dev mode - sử dụng ID test
          console.log("Running in dev mode, using test user_id");
          const testUserData = { id: 1, zaloId: "dev_user", name: "Test User" };
          setUserData(testUserData);
          setUser({ 
            id: testUserData.id, 
            name: testUserData.name || 'Khách', 
            points: 0, 
            spin_tickets: 0 
          });
          setUserSaved(true);
        } else {
          // Production - lấy thông tin người dùng Zalo thực tế
          const result = await getUserInfo({});
          console.log("Zalo user info:", result);
          
          if (result && result.userInfo && result.userInfo.id) {
            const zaloId = result.userInfo.id;
            
            // Lưu thông tin người dùng vào hệ thống
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
              // Lưu ID từ database từ response API
              const savedUserData = {
                id: saveResult.user.id,
                zaloId: zaloId,
                name: saveResult.user.name,
                avatar: result.userInfo.avatar
              };
              setUserData(savedUserData);
              setUser({ 
                id: savedUserData.id, 
                name: savedUserData.name || 'Khách',
                avatar: savedUserData.avatar,
                points: saveResult.user.points || 0,
                spin_tickets: saveResult.user.spin_tickets || 0
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

  // Lấy danh sách nhiệm vụ khi có thông tin người dùng
  useEffect(() => {
    console.log("MissionPage - useEffect triggered", { userData, userSaved });
    
    const fetchMissions = async () => {
      setLoading(true);
      try {
        const userId = userData?.id || user.id;
        console.log("Fetching missions with userId:", userId);
        const response = await getMissions(userId || '');
        console.log("API Response:", response);
        
        if (response.success) {
          console.log("Setting missions:", response.data);
          setMissions(response.data || []);
        } else {
          console.error("API Error:", response.message);
          toast.error(response.message || 'Không thể tải danh sách nhiệm vụ');
        }
      } catch (error) {
        console.error('Lỗi khi tải nhiệm vụ:', error);
        toast.error('Đã xảy ra lỗi khi tải danh sách nhiệm vụ');
      } finally {
        setLoading(false);
      }
    };

    if (userData?.id || user.id) {
      fetchMissions();
    }
    
  }, [userData, userSaved, user.id, setMissions]);

  // Xử lý hoàn thành nhiệm vụ
  const handleCompleteMission = async (missionId: number, actionData?: any) => {
    const userId = userData?.id || user.id;
    if (!userId) {
      toast.error('Vui lòng đăng nhập để thực hiện nhiệm vụ');
      return;
    }

    setLoadingMission(missionId);
    try {
      console.log("Completing mission:", missionId, "with actionData:", actionData);
      const response = await completeMission(missionId, userId, actionData);
      console.log("Complete mission response:", response);
      
      if (response.success) {
        // Cập nhật danh sách nhiệm vụ
        const updatedMissions = missions.map(mission => 
          mission.id === missionId 
            ? { ...mission, status: 'completed' as const, completed_at: new Date().toISOString() } 
            : mission
        );
        setMissions(updatedMissions);

        // Hiển thị phần thưởng
        const mission = missions.find(m => m.id === missionId);
        if (mission) {
          setCurrentReward({
            points: mission.points_reward,
            spin_tickets: mission.spin_tickets,
            name: `${mission.points_reward} điểm${mission.spin_tickets > 0 ? ` + ${mission.spin_tickets} lượt quay` : ''}`
          });
          setShowRewardModal(true);
        }

        // Cập nhật điểm và lượt quay nếu có
        if (response.data?.user) {
          setUser(prev => ({
            ...prev,
            points: response.data.user.points || prev.points,
            spin_tickets: response.data.user.spin_tickets || prev.spin_tickets
          }));
        }
        
        toast.success(response.message || 'Hoàn thành nhiệm vụ thành công!');
      } else {
        toast.error(response.message || 'Không thể hoàn thành nhiệm vụ');
      }
    } catch (error) {
      console.error('Lỗi khi hoàn thành nhiệm vụ:', error);
      toast.error('Đã xảy ra lỗi khi hoàn thành nhiệm vụ');
    } finally {
      setLoadingMission(null);
    }
  };

  // Xử lý thực hiện nhiệm vụ
  const handleMissionAction = (mission: typeof missions[0]) => {
    console.log("Handling mission action:", mission);
    
    // Đặt nhiệm vụ đang thực hiện
    setActiveMission(mission);
    
    const userId = userData?.id || user.id;
    if (!userId) {
      toast.error('Vui lòng đăng nhập để thực hiện nhiệm vụ');
      return;
    }

    // Lấy dữ liệu cấu hình từ action_data (nếu có)
    const actionData = mission.action_data ? JSON.parse(mission.action_data) : {};
    console.log("Action data:", actionData);

    // Xử lý các loại nhiệm vụ khác nhau
    switch (mission.action_required) {
      case 'login_daily':
        // Nhiệm vụ đăng nhập hàng ngày tự động hoàn thành
        trackMissionProgress({
          userId,
          missionId: mission.id,
          actionType: 'login',
          actionData: { login_time: new Date().toISOString() }
        }).then(response => {
          if (response.success && response.data.completed) {
            handleMissionCompleted(mission.id, response);
          } else {
            toast('Đã ghi nhận đăng nhập hàng ngày', {
              icon: 'ℹ️',
              duration: 3000
            });
          }
        });
        break;
        
      case 'login_streak':
        // Thêm đăng nhập liên tiếp và hiển thị thông tin
        trackMissionProgress({
          userId,
          missionId: mission.id,
          actionType: 'login',
          actionData: { login_time: new Date().toISOString() }
        }).then(response => {
          // Hiển thị thông tin tiến trình
          getMissionProgress(userId, mission.id).then(progressResponse => {
            if (progressResponse.success) {
              const progress = progressResponse.data.progress || 0;
              const daysRequired = actionData.required_count || 7;
              const daysCompleted = Math.floor((progress / 100) * daysRequired);
              
              toast(`Đã đăng nhập ${daysCompleted}/${daysRequired} ngày liên tiếp`, {
                icon: 'ℹ️',
                duration: 5000 
              });
              
              if (response.data.completed) {
                handleMissionCompleted(mission.id, response);
              }
            }
          });
        });
        break;
        
      case 'complete_profile':
        // Chuyển đến trang hồ sơ
        navigate('/account/profile');
        toast('Cập nhật đầy đủ hồ sơ và quay lại đánh dấu hoàn thành', { 
          icon: 'ℹ️',
          duration: 5000
        });
        
        // Hiển thị modal xác nhận khi người dùng quay lại
        setShowConfirmModal(mission.id);
        break;
        
      case 'follow_zalo_page':
        // Mở trang Zalo OA với URL từ action_data hoặc mặc định
        const zaloUrl = actionData.url || 'https://zalo.me/4518889858525359159';
        window.open(zaloUrl, '_blank');
        toast('Sau khi theo dõi, quay lại và bấm "Đã hoàn thành"', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'follow_tiktok':
        // Mở TikTok với URL từ action_data hoặc mặc định
        const tiktokUrl = actionData.url || 'https://www.tiktok.com/@agrijapan';
        window.open(tiktokUrl, '_blank');
        toast('Sau khi theo dõi TikTok, quay lại và bấm "Đã hoàn thành"', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'read_articles':
        // Chuyển đến trang bài viết
        navigate('/articles');
        
        // Hàm mô phỏng tạo tiến trình đọc bài viết
        // Trong thực tế, cần được thêm trong component ArticlePage
        const simulateArticleReading = () => {
          getMissionProgress(userId, mission.id).then(progressResponse => {
            if (progressResponse.success) {
              const progress = progressResponse.data.progress || 0;
              
              if (progress < 100) {
                // Mô phỏng đọc 1 bài viết
                const articleId = Math.floor(Math.random() * 100) + 1; // Giả lập ID bài viết
                markArticleAsRead({
                  userId,
                  articleId,
                  readTime: 60, // 60 giây
                  missionId: mission.id
                }).then(readResponse => {
                  if (readResponse.success) {
                    const updatedProgress = readResponse.data.progress || progress;
                    const articlesRequired = actionData.required_count || 3;
                    const articlesRead = Math.floor((updatedProgress / 100) * articlesRequired);
                    
                    toast(`Đã đọc ${articlesRead}/${articlesRequired} bài viết`, {
                      icon: '📖',
                      duration: 3000
                    });
                    
                    if (readResponse.data.completed) {
                      handleMissionCompleted(mission.id, readResponse);
                    }
                  }
                });
              } else {
                toast('Bạn đã hoàn thành nhiệm vụ đọc bài viết!', {
                  icon: '✅',
                  duration: 3000
                });
              }
            }
          });
        };
        
        toast('Đọc 3 bài viết và quay lại đánh dấu hoàn thành. (Mô phỏng)', {
          icon: 'ℹ️',
          duration: 5000
        });
        
        // Mô phỏng đọc bài viết (chỉ cho mục đích demo)
        simulateArticleReading();
        
        setShowConfirmModal(mission.id);
        break;
        
      case 'watch_youtube':
        // Mở video YouTube với URL từ action_data hoặc mặc định
        const youtubeUrl = actionData.url || 'https://www.youtube.com/channel/AgriJapan';
        window.open(youtubeUrl, '_blank');
        
        // Hàm mô phỏng xem video
        const simulateVideoWatching = () => {
          const videoId = 'sample_video_' + Date.now(); // Giả lập video ID
          const watchDuration = actionData.watch_duration || 60; // Thời gian xem yêu cầu từ action_data hoặc mặc định 60s
          
          markVideoWatched({
            userId,
            videoId,
            watchDuration, 
            missionId: mission.id
          }).then(response => {
            if (response.success) {
              toast(`Đã ghi nhận xem video trong ${watchDuration} giây!`, {
                icon: '🎬',
                duration: 3000
              });
              
              if (response.data.mission_status === 'completed') {
                handleMissionCompleted(mission.id, response);
              }
            }
          });
        };
        
        toast('Sau khi xem video, quay lại và bấm "Đã hoàn thành". (Mô phỏng)', {
          icon: 'ℹ️',
          duration: 5000
        });
        
        // Mô phỏng xem video (chỉ cho mục đích demo)
        simulateVideoWatching();
        
        setShowConfirmModal(mission.id);
        break;
        
      case 'quiz_completion':
        // Chuyển đến trang trắc nghiệm
        navigate('/games/quiz');
        toast('Hoàn thành trắc nghiệm với độ chính xác 80% để nhận thưởng', {
          icon: 'ℹ️',
          duration: 5000
        });
        break;
        
      case 'share_app':
        // Mở chia sẻ nếu có API chia sẻ
        if (navigator.share) {
          navigator.share({
            title: 'AgriJapan App',
            text: 'Ứng dụng AgriJapan - Kiến thức nông nghiệp Nhật Bản',
            url: window.location.origin
          }).then(() => {
            // Ghi nhận chia sẻ
            trackMissionProgress({
              userId,
              missionId: mission.id,
              actionType: 'share_app',
              actionData: { shared_at: new Date().toISOString() }
            }).then(response => {
              if (response.success && response.data.completed) {
                handleMissionCompleted(mission.id, response);
              } else {
                toast.success('Đã ghi nhận chia sẻ ứng dụng');
                setShowConfirmModal(mission.id);
              }
            });
          }).catch(err => {
            console.error('Lỗi khi chia sẻ:', err);
            toast.error('Không thể mở tính năng chia sẻ');
            setShowConfirmModal(mission.id);
          });
        } else {
          // Nếu không có API chia sẻ, hiển thị modal xác nhận
          toast('Chia sẻ ứng dụng với ít nhất 3 người bạn, sau đó bấm "Đã hoàn thành"', {
            icon: 'ℹ️',
            duration: 5000
          });
          setShowConfirmModal(mission.id);
        }
        break;
        
      case 'share_facebook':
        // Mở trang chia sẻ Facebook với URL từ action_data hoặc URL mặc định
        const shareUrl = actionData.url ? encodeURIComponent(actionData.url) : encodeURIComponent(window.location.origin);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`, '_blank');
        
        toast('Sau khi chia sẻ, quay lại và bấm "Đã hoàn thành"', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'comment_posts':
        // Chuyển đến trang bài viết
        navigate('/articles');
        
        // Hàm mô phỏng bình luận
        const simulateCommenting = () => {
          getMissionProgress(userId, mission.id).then(progressResponse => {
            if (progressResponse.success) {
              const progress = progressResponse.data.progress || 0;
              
              if (progress < 100) {
                // Mô phỏng thêm bình luận
                const articleId = Math.floor(Math.random() * 100) + 1; // Giả lập ID bài viết
                const commentText = 'Đây là bình luận mẫu cho mục đích demo ' + Date.now();
                
                addArticleComment({
                  userId,
                  articleId,
                  commentText,
                  missionId: mission.id
                }).then(commentResponse => {
                  if (commentResponse.success) {
                    const updatedProgress = commentResponse.data.progress || progress;
                    const commentsRequired = actionData.required_count || 3;
                    const commentsAdded = Math.floor((updatedProgress / 100) * commentsRequired);
                    
                    toast(`Đã bình luận ${commentsAdded}/${commentsRequired} bài viết`, {
                      icon: '💬',
                      duration: 3000
                    });
                    
                    if (commentResponse.data.completed) {
                      handleMissionCompleted(mission.id, commentResponse);
                    }
                  }
                });
              } else {
                toast('Bạn đã hoàn thành nhiệm vụ bình luận!', {
                  icon: '✅',
                  duration: 3000
                });
              }
            }
          });
        };
        
        toast('Bình luận vào 3 bài viết và quay lại đánh dấu hoàn thành. (Mô phỏng)', {
          icon: 'ℹ️',
          duration: 5000
        });
        
        // Mô phỏng thêm bình luận (chỉ cho mục đích demo)
        simulateCommenting();
        
        setShowConfirmModal(mission.id);
        break;
        
      // Các trường hợp khác giữ nguyên
      case 'tiktok_review':
        // Mở TikTok với URL từ action_data hoặc mặc định
        const tiktokReviewUrl = actionData.url || 'https://www.tiktok.com/';
        window.open(tiktokReviewUrl, '_blank');
        toast('Sau khi đăng video review, quay lại và bấm "Đã hoàn thành"', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'complete_survey':
        // Chuyển đến trang khảo sát
        navigate('/survey');
        toast('Hoàn thành khảo sát và quay lại đánh dấu hoàn thành', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'join_livestream':
        // Mở trang livestream với URL từ action_data hoặc mặc định
        const livestreamUrl = actionData.url || 'https://www.youtube.com/live/AgriJapan';
        window.open(livestreamUrl, '_blank');
        toast('Sau khi tham gia livestream, quay lại và bấm "Đã hoàn thành"', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'submit_article':
        // Chuyển đến trang đóng góp bài viết
        navigate('/submit-article');
        toast('Sau khi đóng góp bài viết, quay lại và bấm "Đã hoàn thành"', {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
        
      case 'refer_users':
        // Mở chia sẻ
        if (navigator.share) {
          navigator.share({
            title: 'AgriJapan App',
            text: 'Giới thiệu bạn bè tham gia ứng dụng AgriJapan',
            url: window.location.origin
          }).then(() => {
            setShowConfirmModal(mission.id);
          }).catch(err => {
            console.error('Lỗi khi chia sẻ:', err);
            toast.error('Không thể mở tính năng chia sẻ');
          });
        } else {
          const requiredCount = actionData.required_count || 5;
          toast(`Giới thiệu ${requiredCount} người bạn đăng ký, sau đó bấm "Đã hoàn thành"`, {
            icon: 'ℹ️',
            duration: 5000
          });
          setShowConfirmModal(mission.id);
        }
        break;
        
      case 'reach_points':
        // Hiển thị thông tin điểm hiện tại
        const currentPoints = user.points || 0;
        const requiredPoints = actionData.required_points || 500;
        
        toast(`Điểm hiện tại của bạn: ${currentPoints}/${requiredPoints}. Tiếp tục hoạt động để đạt mục tiêu.`, {
          icon: 'ℹ️',
          duration: 5000
        });
        
        // Kiểm tra tiến trình
        trackMissionProgress({
          userId,
          missionId: mission.id,
          actionType: 'check_points'
        }).then(response => {
          if (response.success && response.data.completed) {
            handleMissionCompleted(mission.id, response);
          }
        });
        break;
        
      default:
        // Với các nhiệm vụ khác, hiển thị modal xác nhận
        toast(`Thực hiện nhiệm vụ: ${mission.name}, sau đó bấm "Đã hoàn thành"`, {
          icon: 'ℹ️',
          duration: 5000
        });
        setShowConfirmModal(mission.id);
        break;
    }
  };

  // Xử lý khi một nhiệm vụ hoàn thành
  const handleMissionCompleted = (missionId: number, response: any) => {
    // Cập nhật danh sách nhiệm vụ
    const updatedMissions = missions.map(mission => 
      mission.id === missionId 
        ? { ...mission, status: 'completed' as const, completed_at: new Date().toISOString() } 
        : mission
    );
    setMissions(updatedMissions);

    // Hiển thị phần thưởng
    const mission = missions.find(m => m.id === missionId);
    if (mission) {
      setCurrentReward({
        points: mission.points_reward,
        spin_tickets: mission.spin_tickets,
        name: `${mission.points_reward} điểm${mission.spin_tickets > 0 ? ` + ${mission.spin_tickets} lượt quay` : ''}`
      });
      setShowRewardModal(true);
    }

    // Cập nhật điểm và lượt quay nếu có
    if (response?.data?.user) {
      setUser(prev => ({
        ...prev,
        points: response.data.user.points || prev.points,
        spin_tickets: response.data.user.spin_tickets || prev.spin_tickets
      }));
    }
    
    toast.success('Hoàn thành nhiệm vụ thành công!');
  };

  // Lọc nhiệm vụ theo trạng thái
  const filteredMissions = missions.filter(mission => {
    if (filter === 'all') return true;
    return mission.status === filter;
  });

  console.log("Rendered missions:", { 
    filter, 
    allMissions: missions.length, 
    filteredMissions: filteredMissions.length 
  });

  return (
    <div className="mission-page">
      <div className="p-4 bg-white">
        <h2 className="text-xl font-bold mb-4">Nhiệm Vụ Hàng Ngày</h2>
        
        {/* Hiển thị thông tin người dùng */}
        {userData && (
          <div className="bg-gradient-to-r from-purple-50 to-indigo-50 p-3 rounded-lg mb-4 flex items-center">
            <div className="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 mr-3 overflow-hidden">
              {userData.avatar ? (
                <img src={userData.avatar} alt="Avatar" className="w-full h-full object-cover" />
              ) : (
                <div className="w-full h-full flex items-center justify-center bg-indigo-100 text-indigo-600">
                  {userData.name?.substring(0, 1) || 'U'}
                </div>
              )}
            </div>
            <div>
              <p className="font-medium">{userData.name || 'Người dùng'}</p>
              <div className="flex text-xs text-gray-500 mt-1">
                <span className="mr-3">{user.points || 0} điểm</span>
                <span>{user.spin_tickets || 0} lượt quay</span>
              </div>
            </div>
          </div>
        )}
        
        <p className="mb-4 text-gray-500">
          Hoàn thành các nhiệm vụ để nhận thưởng! Các phần thưởng bao gồm điểm tích lũy và lượt quay vòng quay may mắn.
        </p>

        <div className="mb-4">
          <div className="flex border-b overflow-x-auto pb-1">
            <button 
              className={`px-4 py-2 whitespace-nowrap ${filter === 'all' ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : 'text-gray-500'}`}
              onClick={() => setFilter('all')}
            >
              Tất cả
            </button>
            <button 
              className={`px-4 py-2 whitespace-nowrap ${filter === 'available' ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : 'text-gray-500'}`}
              onClick={() => setFilter('available')}
            >
              Khả dụng
            </button>
            <button 
              className={`px-4 py-2 whitespace-nowrap ${filter === 'in_progress' ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : 'text-gray-500'}`}
              onClick={() => setFilter('in_progress')}
            >
              Đang thực hiện
            </button>
            <button 
              className={`px-4 py-2 whitespace-nowrap ${filter === 'completed' ? 'border-b-2 border-blue-500 text-blue-500 font-medium' : 'text-gray-500'}`}
              onClick={() => setFilter('completed')}
            >
              Đã hoàn thành
            </button>
          </div>
        </div>

        {loading ? (
          <div className="text-center my-8 py-8">
            <div className="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <p className="mt-2 text-gray-500">Đang tải nhiệm vụ...</p>
          </div>
        ) : filteredMissions.length === 0 ? (
          <div className="text-center my-4">
            <p>Không có nhiệm vụ nào {filter !== 'all' ? 'ở trạng thái này' : ''}</p>
            <p className="text-xs text-gray-500 mt-2">Tổng số nhiệm vụ: {missions.length}</p>
          </div>
        ) : (
          <div className="flex flex-col space-y-4 mt-4">
            {filteredMissions.map(mission => (
              <div key={mission.id} className="border p-3 rounded-lg shadow-sm bg-white">
                <div className="flex flex-col space-y-3">
                  <div className="flex justify-between items-center">
                    <h3 className="font-bold">{mission.name}</h3>
                    {mission.status === 'completed' && (
                      <div className="text-green-500">
                        <Icon icon="zi-check-circle" />
                      </div>
                    )}
                  </div>
                  
                  <p className="text-sm text-gray-500">{mission.description}</p>
                  
                  <hr className="my-2" />
                  
                  <div className="flex flex-wrap gap-3">
                    <div className="flex items-center bg-green-50 py-1 px-2 rounded">
                      <Icon icon="zi-star" />
                      <span className="text-sm ml-1">{mission.points_reward} điểm</span>
                    </div>
                    
                    <div className="flex items-center bg-purple-50 py-1 px-2 rounded">
                      <Icon icon="zi-star" />
                      <span className="text-sm ml-1">{mission.spin_tickets} lượt quay</span>
                    </div>
                    
                    {mission.status === 'completed' && mission.completed_at && (
                      <div className="flex items-center bg-gray-50 py-1 px-2 rounded">
                        <Icon icon="zi-calendar" />
                        <span className="text-sm ml-1">
                          {new Date(mission.completed_at).toLocaleDateString('vi-VN')}
                        </span>
                      </div>
                    )}
                  </div>
                  
                  {mission.status !== 'completed' && (
                    <button
                      className={`px-4 py-2 rounded-lg text-white ${
                        loadingMission === mission.id 
                          ? 'bg-gray-500' 
                          : 'bg-blue-500 hover:bg-blue-600'
                      }`}
                      disabled={loadingMission === mission.id}
                      onClick={() => handleMissionAction(mission)}
                    >
                      {loadingMission === mission.id 
                        ? 'Đang xử lý...' 
                        : mission.status === 'in_progress' 
                          ? 'Tiếp tục thực hiện' 
                          : 'Thực hiện nhiệm vụ'
                      }
                    </button>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
        
        {/* Modal xác nhận hoàn thành nhiệm vụ */}
        {showConfirmModal && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white p-4 rounded-lg max-w-sm w-full">
              <h3 className="font-bold mb-2">Xác nhận hoàn thành</h3>
              <p className="mb-4">Bạn đã hoàn thành nhiệm vụ này chưa?</p>
              <div className="flex justify-end space-x-2">
                <button 
                  className="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg"
                  onClick={() => setShowConfirmModal(null)}
                >
                  Chưa
                </button>
                <button 
                  className="px-4 py-2 bg-blue-500 text-white rounded-lg"
                  onClick={() => {
                    const mission = missions.find(m => m.id === showConfirmModal);
                    if (mission && userData) {
                      const userId = userData.id || user.id;
                      
                      // Sử dụng trackMissionProgress thay vì handleCompleteMission
                      trackMissionProgress({
                        userId,
                        missionId: mission.id,
                        actionType: 'manually_confirmed',
                        actionData: {
                          manually_completed: true, 
                          action_type: mission.action_required,
                          confirmation_time: new Date().toISOString()
                        }
                      }).then(response => {
                        if (response.success && response.data.completed) {
                          handleMissionCompleted(mission.id, response);
                        } else {
                          toast('Đã ghi nhận hành động. Hãy tiếp tục hoàn thành nhiệm vụ để nhận thưởng.', {
                            icon: 'ℹ️', 
                            duration: 3000
                          });
                        }
                      }).catch(error => {
                        console.error('Lỗi khi cập nhật tiến trình:', error);
                        toast.error('Đã xảy ra lỗi khi cập nhật tiến trình nhiệm vụ');
                      });
                    }
                    setShowConfirmModal(null);
                  }}
                >
                  Đã hoàn thành
                </button>
              </div>
            </div>
          </div>
        )}
        
        {/* Modal hiển thị phần thưởng */}
        {showRewardModal && currentReward && (
          <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div className="bg-white p-4 rounded-lg max-w-sm w-full text-center">
              <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Icon icon="zi-check" className="text-green-500 text-3xl" />
              </div>
              <h3 className="text-xl font-bold mb-2">Nhiệm vụ hoàn thành!</h3>
              <p className="mb-4">
                Bạn đã nhận được phần thưởng:
                <span className="block font-bold text-green-600 mt-2">
                  {currentReward.name}
                </span>
              </p>
              <button 
                className="w-full py-2 bg-blue-500 text-white rounded-lg"
                onClick={() => setShowRewardModal(false)}
              >
                Đóng
              </button>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

export default MissionPage;
