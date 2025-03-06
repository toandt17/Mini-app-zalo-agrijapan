import React, { useState } from 'react';
import { useAtom, useAtomValue } from 'jotai';
import { quizQuestionsState, gameHistoryState, luckyWheelState } from '@/state';
import { Button, Icon } from 'zmp-ui';
import confetti from 'canvas-confetti';

export default function QuizPage() {
  const questions = useAtomValue(quizQuestionsState);
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedOption, setSelectedOption] = useState<number | null>(null);
  const [isCorrect, setIsCorrect] = useState<boolean | null>(null);
  const [gameHistory, setGameHistory] = useAtom(gameHistoryState);
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  
  const currentQuestion = questions[currentQuestionIndex];
  
  const handleAnswerSelect = (optionIndex: number) => {
    if (isCorrect !== null) return; // Already answered
    
    setSelectedOption(optionIndex);
    const correct = optionIndex === currentQuestion.correctAnswer;
    setIsCorrect(correct);
    
    if (correct) {
      // Add reward to history if answer is correct
      setGameHistory([
        {
          id: Date.now(),
          type: 'quiz',
          result: `Trả lời đúng: ${currentQuestion.reward.name}`,
          reward: currentQuestion.reward,
          createdAt: new Date()
        },
        ...gameHistory
      ]);
      
      // Extra rewards
      if (currentQuestion.reward.type === 'point') {
        // Points already recorded in history
      } else if (currentQuestion.reward.type === 'voucher') {
        // Add voucher logic here
      }
      
      // Trigger confetti animation
      setTimeout(() => {
        confetti({
          particleCount: 100,
          spread: 70,
          origin: { y: 0.6 }
        });
      }, 300);
    }
  };
  
  const handleNext = () => {
    if (currentQuestionIndex < questions.length - 1) {
      setCurrentQuestionIndex(currentQuestionIndex + 1);
      setSelectedOption(null);
      setIsCorrect(null);
    } else {
      // Quiz completed - maybe add an extra reward
      setLuckyWheel({
        ...luckyWheel,
        remainingSpins: luckyWheel.remainingSpins + 1
      });
      
      // Update history
      setGameHistory([
        {
          id: Date.now(),
          type: 'quiz',
          result: `Hoàn thành quiz: +1 lượt quay`,
          reward: {
            id: 999,
            name: "+1 lượt quay",
            type: "point",
            value: 1,
            color: "#4CAF50"
          },
          createdAt: new Date()
        },
        ...gameHistory
      ]);
    }
  };
  
  return (
    <div className="flex flex-col p-4 h-full">
      <div className="bg-gradient-to-r from-blue-100 to-indigo-100 w-full rounded-xl p-4 mb-6">
        <div className="flex justify-between items-center">
          <h2 className="text-xl font-bold text-blue-800">Câu hỏi {currentQuestionIndex + 1}/{questions.length}</h2>
          <div className="text-blue-600 text-sm">
            Phần thưởng: <span className="font-bold">{currentQuestion.reward.name}</span>
          </div>
        </div>
      </div>
      
      {/* Question */}
      <div className="bg-white rounded-xl p-4 shadow-md mb-6">
        <h3 className="text-lg font-bold mb-4">{currentQuestion.question}</h3>
        
        <div className="space-y-3">
          {currentQuestion.options.map((option, index) => (
            <button
              key={index}
              className={`w-full text-left p-4 rounded-lg border ${
                selectedOption === index
                  ? isCorrect 
                    ? 'bg-green-100 border-green-500' 
                    : 'bg-red-100 border-red-500'
                  : 'border-gray-200 hover:border-blue-500'
              } ${
                isCorrect !== null && index === currentQuestion.correctAnswer
                  ? 'bg-green-100 border-green-500'
                  : ''
              }`}
              onClick={() => handleAnswerSelect(index)}
              disabled={isCorrect !== null}
            >
              <div className="flex items-center">
                <div className={`w-6 h-6 rounded-full mr-3 flex items-center justify-center ${
                  selectedOption === index
                    ? isCorrect 
                      ? 'bg-green-500 text-white' 
                      : 'bg-red-500 text-white'
                    : 'bg-gray-100'
                } ${
                  isCorrect !== null && index === currentQuestion.correctAnswer
                    ? 'bg-green-500 text-white'
                    : ''
                }`}>
                  {selectedOption === index ? (
                    isCorrect ? <Icon icon="zi-check" /> : <Icon icon="zi-close" />
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
        {isCorrect !== null && (
          <div className={`mt-4 p-3 rounded-lg ${
            isCorrect ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'
          }`}>
            {isCorrect 
              ? <>
                  <span className="font-bold">Chính xác!</span> Bạn đã nhận được: {currentQuestion.reward.name}
                </>
              : <>
                  <span className="font-bold">Chưa chính xác!</span> Đáp án đúng là: {
                    String.fromCharCode(65 + currentQuestion.correctAnswer)
                  } - {currentQuestion.options[currentQuestion.correctAnswer]}
                </>
            }
          </div>
        )}
      </div>
      
      {/* Next button */}
      {isCorrect !== null && (
        <Button 
          className="w-full h-12 rounded-lg text-lg font-bold bg-blue-500"
          onClick={handleNext}
        >
          {currentQuestionIndex < questions.length - 1 ? 'Câu hỏi tiếp theo' : 'Hoàn thành'}
        </Button>
      )}
    </div>
  );
} 