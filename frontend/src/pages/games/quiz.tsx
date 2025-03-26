import React, { useState, useEffect } from 'react';
import { useAtom } from 'jotai';
import { userState, luckyWheelState } from '@/state';
import { Button, Icon, Spinner, useSnackbar } from 'zmp-ui';
import confetti from 'canvas-confetti';
import { getQuizQuestions, answerQuizQuestion, clearQuizQuestionsCache, saveUser } from '@/api/gameApi';
import { getUserInfo } from 'zmp-sdk';

interface QuestionType {
  id: number;
  question: string;
  options: string[];
  correct_answer: number | string;
  difficulty: string;
  points_reward: number;
  spin_tickets: number;
  reward: {
    id: number;
    name: string;
    description?: string;
    image?: string;
  } | null;
  answered?: boolean;
  answered_correctly?: boolean;
}

interface AnswerResult {
  is_correct: boolean;
  selected_option: number;
  correct_answer: number | string;
  points_earned: number;
  spin_tickets: number;
  next_question_id?: number;
  reward: {
    id: number;
    name: string;
    description?: string;
    image?: string;
  } | null;
  already_answered?: boolean;
}

interface QuestionOrder {
  currentIndex: number;
  questionSequence: number[];
}

// Hàm helper để chuyển đổi correct_answer từ chuỗi sang số
const convertCorrectAnswer = (answer: string | number): number => {
  console.log('Converting answer:', answer, 'type:', typeof answer);
  
  if (typeof answer === 'number') return answer;
  
  // Chuyển a,b,c,d thành 0,1,2,3
  switch(answer.toString().toLowerCase()) {
    case 'a': return 0;
    case 'b': return 1;
    case 'c': return 2;
    case 'd': return 3;
    default: 
      // Nếu là số dạng chuỗi, chuyển về số
      const num = parseInt(answer.toString());
      console.log('Parsed number:', num, 'from string:', answer);
      return isNaN(num) ? 0 : num;
  }
};

// Component hướng dẫn
const QuizHeader = () => {
  const [showGuide, setShowGuide] = useState(false);
  
  return (
    <div className="mb-4">
      <div className="flex justify-between items-center mb-2">
        <h1 className="text-xl font-bold text-blue-800">Trắc nghiệm nông nghiệp</h1>
        <button 
          onClick={() => setShowGuide(!showGuide)}
          className="p-2 text-blue-600 hover:bg-blue-50 rounded-full"
        >
          {showGuide ? '❌' : '❓'}
        </button>
      </div>
      
      {showGuide && (
        <div className="bg-blue-50 p-3 rounded-lg mb-4 text-sm">
          <h3 className="font-bold mb-2">Hướng dẫn:</h3>
          <ol className="list-decimal pl-5 space-y-1">
            <li>Trả lời câu hỏi trắc nghiệm để nhận điểm và phần thưởng</li>
            <li>Trả lời đúng để nhận điểm và lượt quay may mắn</li>
            <li>Sau khi hoàn thành một bộ câu hỏi, bạn có thể tải bộ câu hỏi mới</li>
            <li>Điểm và lượt quay sẽ được tích lũy vào tài khoản của bạn</li>
          </ol>
        </div>
      )}
    </div>
  );
};

export default function QuizPage() {
  const [user, setUser] = useAtom(userState);
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  const { openSnackbar } = useSnackbar();
  
  // State để quản lý dữ liệu người dùng từ API
  const [userData, setUserData] = useState<{id: number, zaloId: string | number, name?: string} | null>(null);
  const [userSaved, setUserSaved] = useState(false);
  
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
          
          // Cập nhật state user global
          setUser({
            id: "1",
            name: "Test User"
          });
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
              
              // Cập nhật state user global
              setUser({
                id: String(saveResult.user.id),
                name: saveResult.user.name || "Người dùng"
              });
              
              // Hiển thị toast chào mừng nếu là user mới
              if (saveResult.is_new) {
                openSnackbar({
                  text: `Chào mừng ${saveResult.user.name || "bạn"} đến với ứng dụng!`,
                  type: 'success',
                  duration: 3000
                });
              }
            } else {
              console.error("Error saving user:", saveResult);
              openSnackbar({
                text: "Không thể lưu thông tin người dùng",
                type: 'error',
                duration: 3000
              });
            }
          } else {
            openSnackbar({
              text: "Không thể lấy thông tin người dùng",
              type: 'error',
              duration: 3000
            });
            console.error("Không thể lấy Zalo ID");
          }
        }
      } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
        openSnackbar({
          text: "Không thể lấy thông tin người dùng",
          type: 'error',
          duration: 3000
        });
      }
    }
    
    fetchUserInfo();
  }, []);
  
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
          
          // Cập nhật state user global
          setUser({
            id: String(saveResult.user.id),
            name: saveResult.user.name || "Người dùng"
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
  
  const [questions, setQuestions] = useState<QuestionType[]>([]);
  const [loading, setLoading] = useState(true);
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedOption, setSelectedOption] = useState<number | null>(null);
  const [answerResult, setAnswerResult] = useState<AnswerResult | null>(null);
  const [answering, setAnswering] = useState(false);
  const [completed, setCompleted] = useState(false);
  
  // Thêm state để theo dõi thứ tự câu hỏi
  const [questionOrder, setQuestionOrder] = useState<number[]>([]);
  
  // Add debugging log - sử dụng tham chiếu hàm không phụ thuộc vào state để tránh re-render
  React.useEffect(() => {
    console.log('QuizPage component mounted');
    
    return () => {
      console.log('QuizPage component unmounted');
    };
  }, []);
  
  // Kiểm tra URL để debug
  useEffect(() => {
    const logURLChange = () => {
      console.log('Current location:', window.location.href);
    };
    
    // Log khi component mount
    logURLChange();
    
    // Theo dõi sự thay đổi của URL (nếu cần)
    if (typeof window !== 'undefined') {
      window.addEventListener('popstate', logURLChange);
    }
    
    return () => {
      if (typeof window !== 'undefined') {
        window.removeEventListener('popstate', logURLChange);
      }
    };
  }, []);
  
  // Tải danh sách câu hỏi khi component được mount và sau khi đã xác thực user
  useEffect(() => {
    console.log('QuizPage useEffect for fetching questions triggered');
    if (!userSaved) {
      console.log('Skipping question fetch until user is saved');
      return;
    }
    
    let isMounted = true; // Flag để kiểm tra component đã unmount chưa
    
    const fetchQuestions = async () => {
      try {
        setLoading(true);
        console.log('Starting to fetch questions');
        
        // Gọi API để lấy danh sách câu hỏi
        const response = await getQuizQuestions({
          limit: 5,
          userId: userData?.id
        }, false); // Không bắt buộc làm mới
        
        console.log('Questions fetched, isMounted:', isMounted);
        if (isMounted) { // Chỉ cập nhật state nếu component vẫn mounted
          if (response.success) {
            setQuestions(response.data.questions);
            // Lưu ID của tất cả câu hỏi theo thứ tự
            setQuestionOrder(response.data.questions.map(q => q.id));
            
            // Xử lý tiếp tục từ câu hỏi đang hoạt động
            const currentProgress = response.data.current_progress;
            if (currentProgress && currentProgress.has_active_question && currentProgress.next_question_id > 0) {
              // Tìm index của câu hỏi tiếp theo cần trả lời
              const activeQuestionIndex = response.data.questions.findIndex(
                q => q.id === currentProgress.next_question_id
              );
              
              if (activeQuestionIndex !== -1) {
                console.log('Resuming from active question at index:', activeQuestionIndex);
                setCurrentQuestionIndex(activeQuestionIndex);
              } else {
                console.log('Active question not found in returned questions, starting from beginning');
                setCurrentQuestionIndex(0);
              }
            } else {
              // Không có câu hỏi đang hoạt động, bắt đầu từ đầu
              console.log('No active question found, starting from beginning');
              setCurrentQuestionIndex(0);
            }
            
            setCompleted(false);
          } else {
            openSnackbar({
              text: response.message || 'Không thể tải câu hỏi',
              type: 'error',
              duration: 3000
            });
          }
        }
      } catch (error) {
        if (isMounted) {
          console.error('Error fetching questions:', error);
          openSnackbar({
            text: 'Có lỗi xảy ra khi tải câu hỏi',
            type: 'error',
            duration: 3000
          });
        }
      } finally {
        if (isMounted) {
          setLoading(false);
        }
      }
    };
    
    fetchQuestions();
    
    // Cleanup function
    return () => {
      console.log('QuizPage component unmounting');
      isMounted = false; // Đánh dấu component đã unmount
    };
  }, [userSaved]); // Thêm userSaved vào dependency để tải câu hỏi sau khi đã lưu thông tin user
  
  const currentQuestion = questions[currentQuestionIndex];
  
  // Thêm useEffect để log thông tin khi answerResult thay đổi
  useEffect(() => {
    if (answerResult) {
      console.log('Rendering feedback. answerResult:', answerResult);
      console.log('Correct answer (từ API):', answerResult.correct_answer, 'kiểu:', typeof answerResult.correct_answer);
      console.log('Chuyển đổi sang index:', convertCorrectAnswer(answerResult.correct_answer));
      console.log('Already answered:', answerResult.already_answered);
      if (currentQuestion) {
        console.log('Câu hỏi hiện tại:', currentQuestion);
        console.log('Tùy chọn tại index đúng:', currentQuestion.options[convertCorrectAnswer(answerResult.correct_answer)]);
      }
    }
  }, [answerResult, currentQuestion]);
  
  const handleAnswerSelect = async (optionIndex: number) => {
    console.log('handleAnswerSelect called with option:', optionIndex);
    if (answering || answerResult !== null) {
      console.log('Skipping - already answering or has result:', { answering, hasResult: answerResult !== null });
      return;
    }
    
    // Kiểm tra nếu câu hỏi đã được trả lời trước đó và trả lời sai
    if (currentQuestion.answered === true && currentQuestion.answered_correctly === false) {
      console.log('Question was previously answered incorrectly');
      // Đánh dấu là đang trả lời để ngăn các cú nhấp khác
      setAnswering(true);
      
      // Hiển thị thông báo
      openSnackbar({
        text: 'Bạn đã trả lời sai câu hỏi này trước đó',
        type: 'warning',
        duration: 3000
      });
      
      // Lấy đáp án đúng và hiển thị ngay cho người dùng để họ có thể tiếp tục
      setSelectedOption(optionIndex);
      
      // Giả lập kết quả trả lời để hiển thị đáp án đúng
      setAnswerResult({
        is_correct: false,
        selected_option: optionIndex,
        correct_answer: currentQuestion.correct_answer,
        points_earned: 0,
        spin_tickets: 0,
        reward: null,
        already_answered: true
      });
      
      setAnswering(false);
      return;
    }
    
    setSelectedOption(optionIndex);
    setAnswering(true);
    console.log('Set selectedOption and answering state');
    
    try {
      if (!userData || !userData.id) {
        console.error('No user ID found:', userData);
        openSnackbar({
          text: 'Vui lòng đăng nhập để trả lời câu hỏi',
          type: 'error',
          duration: 3000
        });
        setAnswering(false);
        return;
      }
      
      console.log('Calling API with:', {
        userId: userData.id,
        questionId: currentQuestion?.id,
        optionIndex
      });
      
      const response = await answerQuizQuestion({
        userId: userData.id,
        questionId: currentQuestion.id,
        selectedOption: optionIndex
      });
      
      console.log('API response:', response);
      console.log('API response data details:', JSON.stringify(response.data, null, 2));
      
      if (response.success) {
        console.log('Setting answer result:', response.data);
        console.log('Is correct answer present?', response.data.hasOwnProperty('correct_answer'));
        console.log('Correct answer value:', response.data.correct_answer);
        console.log('Converted answer index:', convertCorrectAnswer(response.data.correct_answer));
        
        // Nếu người dùng đã trả lời trước đó, cập nhật selectedOption thành lựa chọn cũ của họ
        if (response.data.already_answered) {
          setSelectedOption(response.data.selected_option);
        }
        
        setAnswerResult(response.data);
        
        // Hiển thị thông báo
        if (response.data.is_correct) {
          // Kiểm tra nếu câu hỏi đã trả lời trước đó
          if (response.data.already_answered) {
            openSnackbar({
              text: 'Bạn đã trả lời đúng trước đó!',
              type: 'success',
              duration: 2000
            });
          } else {
            openSnackbar({
              text: `Đúng! +${response.data.points_earned} điểm`,
              type: 'success',
              duration: 2000
            });
            
            // Nếu trả lời đúng, hiệu ứng confetti
            setTimeout(() => {
              confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
              });
            }, 300);
            
            // Cập nhật số lượt quay nếu nhận được
            if (response.data.spin_tickets > 0) {
              setLuckyWheel(prev => ({
                ...prev,
                remainingSpins: (prev.remainingSpins || 0) + response.data.spin_tickets
              }));
            }
          }
        } else {
          // Nếu trả lời sai
          if (response.data.already_answered) {
            openSnackbar({
              text: 'Bạn đã trả lời sai câu hỏi này trước đó',
              type: 'warning',
              duration: 2000
            });
          } else {
            openSnackbar({
              text: 'Sai rồi! Hãy thử lại.',
              type: 'warning',
              duration: 2000
            });
          }
        }
      } else {
        console.error('API error response:', response);
        
        // Kiểm tra nếu người dùng đang cố trả lời câu hỏi không theo thứ tự
        if (response.data && response.data.next_question_id) {
          // Tìm index của câu hỏi tiếp theo
          const nextQuestionIndex = questions.findIndex(q => q.id === response.data.next_question_id);
          if (nextQuestionIndex !== -1) {
            setCurrentQuestionIndex(nextQuestionIndex);
            openSnackbar({
              text: 'Bạn cần trả lời các câu hỏi theo đúng thứ tự',
              type: 'warning',
              duration: 3000
            });
            setSelectedOption(null);
            setAnswerResult(null);
            return;
          }
        }
        
        // Nếu lỗi liên quan đến user not found, thử lưu lại thông tin user
        if (response.message && response.message.includes("No query results for model")) {
          openSnackbar({
            text: 'Tài khoản chưa được nhận diện, đang thử lại...',
            type: 'error',
            duration: 3000
          });
          
          const retrySave = await retryUserSave();
          if (retrySave) {
            openSnackbar({
              text: 'Đã cập nhật thông tin, vui lòng thử lại',
              type: 'success',
              duration: 3000
            });
          } else {
            openSnackbar({
              text: 'Không thể xác thực tài khoản, vui lòng đăng nhập lại',
              type: 'error',
              duration: 3000
            });
          }
        } else {
          openSnackbar({
            text: response.message || 'Có lỗi xảy ra khi trả lời câu hỏi',
            type: 'error',
            duration: 3000
          });
        }
      }
    } catch (error) {
      console.error('Error answering question:', error);
      openSnackbar({
        text: 'Có lỗi xảy ra khi trả lời câu hỏi',
        type: 'error',
        duration: 3000
      });
    } finally {
      console.log('Finishing answering process');
      setAnswering(false);
    }
  };
  
  // Hàm này chỉ cho phép chuyển sang câu hỏi tiếp theo khi người dùng trả lời đúng
  const handleNext = () => {
    // Kiểm tra xem người dùng đã trả lời đúng chưa
    if (!answerResult || !answerResult.is_correct) {
      openSnackbar({
        text: 'Bạn cần trả lời đúng để tiếp tục',
        type: 'warning',
        duration: 3000
      });
      return;
    }
    
    // Tìm câu hỏi tiếp theo dựa vào next_question_id từ API nếu có
    if (answerResult.next_question_id) {
      const nextIndex = questions.findIndex(q => q.id === answerResult.next_question_id);
      if (nextIndex !== -1) {
        setCurrentQuestionIndex(nextIndex);
        setSelectedOption(null);
        setAnswerResult(null);
        return;
      }
    }
    
    // Nếu không có next_question_id hoặc không tìm thấy, sử dụng cách thông thường
    if (currentQuestionIndex < questions.length - 1) {
      setCurrentQuestionIndex(currentQuestionIndex + 1);
      setSelectedOption(null);
      setAnswerResult(null);
    } else {
      // Đã hoàn thành tất cả câu hỏi
      setCompleted(true);
      openSnackbar({
        text: 'Bạn đã hoàn thành tất cả câu hỏi',
        type: 'success',
        duration: 3000
      });
    }
  };
  
  const handleReloadQuestions = () => {
    // Đặt cờ đang tải để ngăn chặn nhiều lần gọi API
    if (loading) return;
    
    setLoading(true);
    // Xóa state hiện tại để tránh hiển thị dữ liệu cũ trong quá trình tải
    setQuestions([]);
    setSelectedOption(null);
    setAnswerResult(null);
    setCurrentQuestionIndex(0);
    
    // Xóa cache trước khi tải câu hỏi mới
    clearQuizQuestionsCache();
    
    getQuizQuestions({ 
      limit: 5,
      userId: userData?.id 
    }, true) // Bắt buộc làm mới
      .then(response => {
        if (response.success) {
          setQuestions(response.data.questions);
          // Lưu thứ tự câu hỏi khi tải mới
          setQuestionOrder(response.data.questions.map(q => q.id));
          setCurrentQuestionIndex(0);
          setCompleted(false);
        } else {
          openSnackbar({
            text: response.message || 'Không thể tải câu hỏi mới',
            type: 'error',
            duration: 3000
          });
        }
      })
      .catch(error => {
        console.error('Error reloading questions:', error);
        openSnackbar({
          text: 'Có lỗi xảy ra khi tải câu hỏi mới',
          type: 'error',
          duration: 3000
        });
      })
      .then(() => {
        setLoading(false);
      });
  };
  
  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center h-full p-4">
        <Spinner />
        <p className="mt-4 text-gray-600">
          {!userSaved ? 'Đang xác thực thông tin...' : 'Đang tải câu hỏi...'}
        </p>
      </div>
    );
  }
  
  if (questions.length === 0) {
    return (
      <div className="flex flex-col items-center justify-center h-full p-4">
        <div className="text-yellow-500 text-5xl mb-4">⚠️</div>
        <p className="text-gray-600">Không có câu hỏi nào</p>
        <Button 
          className="mt-4"
          onClick={handleReloadQuestions}
        >
          Thử lại
        </Button>
      </div>
    );
  }
  
  if (completed) {
    return (
      <div className="flex flex-col items-center justify-center h-full p-4">
        <div className="text-green-500 text-5xl mb-4">🎉</div>
        <h2 className="text-xl font-bold text-green-800 mb-4">Chúc mừng!</h2>
        <p className="text-gray-600 text-center mb-6">Bạn đã hoàn thành tất cả câu hỏi</p>
        <Button 
          className="mt-4 bg-blue-500"
          onClick={handleReloadQuestions}
        >
          Tải câu hỏi mới
        </Button>
      </div>
    );
  }
  
  return (
    <div className="flex flex-col p-4 h-full">
      <QuizHeader />
      
      <div className="bg-gradient-to-r from-blue-100 to-indigo-100 w-full rounded-xl p-4 mb-6 shadow-sm">
        <div className="flex justify-between items-center">
          <h2 className="text-xl font-bold text-blue-800">Câu hỏi {currentQuestionIndex + 1}/{questions.length}</h2>
          <div className="text-blue-600 text-sm bg-white px-3 py-1 rounded-full shadow-sm">
            {currentQuestion.points_reward > 0 && (
              <span className="mr-2">+{currentQuestion.points_reward} điểm</span>
            )}
            {currentQuestion.spin_tickets > 0 && (
              <span>+{currentQuestion.spin_tickets} lượt quay</span>
            )}
          </div>
        </div>
      </div>
      
      {/* Alert for previously attempted question */}
      {currentQuestion.answered && !currentQuestion.answered_correctly && (
        <div className="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-md">
          <div className="flex">
            <div className="flex-shrink-0">
              <svg className="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
              </svg>
            </div>
            <div className="ml-3">
              <p className="text-sm text-yellow-700">
                Bạn đã trả lời sai câu hỏi này trước đó. Hãy thử lại để tiếp tục.
              </p>
            </div>
          </div>
        </div>
      )}
      
      {/* Question */}
      <div className="bg-white rounded-xl p-4 shadow-md mb-6">
        <h3 className="text-lg font-bold mb-4">{currentQuestion.question}</h3>
        
        <div className="space-y-3">
          {currentQuestion.options.map((option, index) => (
            <button
              key={index}
              className={`w-full text-left p-4 rounded-lg border ${
                selectedOption === index
                  ? answerResult?.is_correct 
                    ? 'bg-green-100 border-green-500' 
                    : 'bg-red-100 border-red-500'
                  : 'border-gray-200 hover:border-blue-500'
              } ${
                answerResult !== null && index === convertCorrectAnswer(answerResult.correct_answer)
                  ? 'bg-green-100 border-green-500'
                  : ''
              }`}
              onClick={() => handleAnswerSelect(index)}
              disabled={answering || answerResult !== null}
            >
              <div className="flex items-center">
                <div className={`w-6 h-6 rounded-full mr-3 flex items-center justify-center ${
                  selectedOption === index
                    ? answerResult?.is_correct 
                      ? 'bg-green-500 text-white' 
                      : 'bg-red-500 text-white'
                    : 'bg-gray-100'
                } ${
                  answerResult !== null && index === convertCorrectAnswer(answerResult.correct_answer)
                    ? 'bg-green-500 text-white'
                    : ''
                }`}>
                  {selectedOption === index && answerResult ? (
                    answerResult.is_correct ? "✓" : "✗"
                  ) : (
                    String.fromCharCode(65 + index) // A, B, C, D...
                  )}
                </div>
                <span>{option}</span>
              </div>
            </button>
          ))}
        </div>
        
        {/* Feedback message */}
        {answerResult && (
          <div className={`mt-4 p-3 rounded-lg ${
            answerResult.is_correct ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'
          }`}>
            <div className="flex items-center mb-2">
              {answerResult.is_correct 
                ? <span className="text-green-500 text-xl mr-2">✓</span>
                : <span className="text-red-500 text-xl mr-2">✗</span>
              }
              <span className="font-bold">
                {answerResult.already_answered 
                  ? (answerResult.is_correct ? 'Bạn đã trả lời đúng trước đó!' : 'Bạn đã trả lời sai trước đó!')
                  : (answerResult.is_correct ? 'Chính xác!' : 'Chưa chính xác!')}
              </span>
            </div>
            
            {answerResult.is_correct ? (
              <div className="flex flex-col">
                <div className="mt-1">
                  {!answerResult.already_answered && answerResult.points_earned > 0 && (
                    <div className="flex items-center">
                      <span className="text-yellow-500 mr-1">⭐</span>
                      <span>+{answerResult.points_earned} điểm</span>
                    </div>
                  )}
                  {!answerResult.already_answered && answerResult.spin_tickets > 0 && (
                    <div className="flex items-center mt-1">
                      <span className="text-blue-500 mr-1">🎡</span>
                      <span>+{answerResult.spin_tickets} lượt quay</span>
                    </div>
                  )}
                  {answerResult.reward && !answerResult.already_answered && (
                    <div className="flex items-center mt-1">
                      <span className="text-purple-500 mr-1">🎁</span>
                      <span>Phần thưởng: {answerResult.reward.name}</span>
                    </div>
                  )}
                </div>
              </div>
            ) : (
              <div className="mt-2">
                <span className="block">Đáp án đúng là:</span>
                <div className="flex items-center mt-1 font-medium">
                  <div className="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center mr-2">
                    {String.fromCharCode(65 + convertCorrectAnswer(answerResult.correct_answer))}
                  </div>
                  <span>
                    {currentQuestion.options[convertCorrectAnswer(answerResult.correct_answer)]}
                  </span>
                </div>
              </div>
            )}
          </div>
        )}
      </div>
      
      {/* Next button */}
      {answerResult !== null && (
        <Button 
          className={`w-full h-12 rounded-lg text-lg font-bold ${
            answerResult.is_correct ? 'bg-blue-500' : 'bg-gray-400'
          }`}
          onClick={handleNext}
          disabled={!answerResult.is_correct}
        >
          {answerResult.is_correct 
            ? (currentQuestionIndex < questions.length - 1 
                ? (answerResult.already_answered ? 'Tiếp tục câu hỏi tiếp theo' : 'Câu hỏi tiếp theo') 
                : 'Hoàn thành')
            : 'Hãy trả lời đúng để tiếp tục'}
        </Button>
      )}
    </div>
  );
} 