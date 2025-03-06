import React, { useState } from 'react';
import { useAtom } from 'jotai';
import { checkInState, luckyWheelState, gameHistoryState } from '@/state';
import { Button, Icon } from 'zmp-ui';
import { format, addDays, isToday } from 'date-fns';

const REWARDS = [
  { day: 1, name: "5 điểm" },
  { day: 2, name: "10 điểm" },
  { day: 3, name: "15 điểm" },
  { day: 4, name: "20 điểm" },
  { day: 5, name: "1 lượt quay" },
  { day: 6, name: "30 điểm" },
  { day: 7, name: "50 điểm + 2 lượt quay" },
];

// Đơn giản hóa confetti nếu chưa cài đặt package
const triggerConfetti = () => {
  console.log('Hiệu ứng confetti kích hoạt');
  // Implement hiệu ứng đơn giản nếu cần
};

export default function CheckInPage() {
  const [checkIn, setCheckIn] = useAtom(checkInState);
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  const [gameHistory, setGameHistory] = useAtom(gameHistoryState);
  const [showReward, setShowReward] = useState(false);
  
  const canCheckInToday = !checkIn.lastCheckIn || 
    new Date(checkIn.lastCheckIn).toDateString() !== new Date().toDateString();
  
  const handleCheckIn = () => {
    if (!canCheckInToday) return;
    
    const today = new Date();
    const isConsecutive = checkIn.lastCheckIn && 
      (new Date(checkIn.lastCheckIn).getTime() > new Date().setHours(0,0,0,0) - 86400000);
    
    const newConsecutive = isConsecutive ? checkIn.consecutive + 1 : 1;
    
    // Update check-in state
    setCheckIn({
      lastCheckIn: today,
      consecutive: newConsecutive,
      total: checkIn.total + 1
    });
    
    // Get reward based on consecutive days
    const dayInCycle = newConsecutive % 7 || 7;
    const reward = REWARDS.find(r => r.day === dayInCycle);
    
    // Add to history
    setGameHistory([
      {
        id: Date.now(),
        type: 'checkin',
        result: `Điểm danh ngày ${dayInCycle}: ${reward?.name}`,
        reward: {
          id: dayInCycle,
          name: reward?.name || "",
          type: "point",
          value: dayInCycle * 5,
          color: "#4CAF50"
        },
        createdAt: new Date()
      },
      ...gameHistory
    ]);
    
    // Give bonus spin if it's day 5 or 7
    if (dayInCycle === 5) {
      setLuckyWheel({
        ...luckyWheel,
        remainingSpins: luckyWheel.remainingSpins + 1
      });
    } else if (dayInCycle === 7) {
      setLuckyWheel({
        ...luckyWheel,
        remainingSpins: luckyWheel.remainingSpins + 2
      });
    }
    
    // Show reward and trigger confetti
    setShowReward(true);
    triggerConfetti();
  };
  
  return (
    <div className="flex flex-col p-4 h-full">
      <div className="bg-gradient-to-r from-green-100 to-teal-100 w-full rounded-xl p-4 mb-6 text-center">
        <h2 className="text-xl font-bold text-green-800 mb-2">Điểm Danh Hàng Ngày</h2>
        <p className="text-green-600">
          Bạn đã điểm danh <span className="font-bold">{checkIn.consecutive}</span> ngày liên tiếp
        </p>
      </div>
      
      {/* Reward popup */}
      {showReward && (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-black/50">
          <div className="bg-white rounded-xl p-6 w-4/5 max-w-xs text-center">
            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <Icon icon="zi-check" className="text-green-500 text-3xl" />
            </div>
            <h3 className="text-xl font-bold mb-2">Điểm danh thành công!</h3>
            <p className="mb-4">
              Bạn đã nhận được phần thưởng cho ngày {checkIn.consecutive % 7 || 7}:
              <span className="block font-bold text-green-600 mt-2">
                {REWARDS.find(r => r.day === (checkIn.consecutive % 7 || 7))?.name}
              </span>
            </p>
            <Button 
              className="w-full bg-green-500"
              onClick={() => setShowReward(false)}
            >
              Đóng
            </Button>
          </div>
        </div>
      )}
      
      {/* Calendar */}
      <div className="bg-white rounded-xl p-4 shadow-md mb-6">
        <div className="grid grid-cols-7 gap-2 mb-4">
          {['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'].map((day, i) => (
            <div key={i} className="text-center text-xs font-medium text-gray-500">
              {day}
            </div>
          ))}
        </div>
        
        <div className="grid grid-cols-7 gap-2">
          {Array.from({ length: 7 }).map((_, index) => {
            const day = index + 1;
            const isActive = checkIn.consecutive >= day;
            const isToday = checkIn.consecutive % 7 === day || (day === 7 && checkIn.consecutive % 7 === 0);
            
            return (
              <div 
                key={day} 
                className={`aspect-square rounded-full flex flex-col items-center justify-center p-1 ${
                  isActive 
                    ? isToday 
                      ? 'bg-green-500 text-white' 
                      : 'bg-green-100 text-green-800' 
                    : 'bg-gray-100 text-gray-400'
                }`}
              >
                <span className="text-xs font-medium">Ngày</span>
                <span className="text-sm font-bold">{day}</span>
              </div>
            );
          })}
        </div>
      </div>
      
      {/* Rewards table */}
      <div className="bg-white rounded-xl p-4 shadow-md mb-6">
        <h3 className="font-bold mb-3">Phần thưởng điểm danh:</h3>
        <div className="space-y-2">
          {REWARDS.map((reward) => {
            const isCompleted = checkIn.consecutive >= reward.day;
            const isCurrent = checkIn.consecutive % 7 === reward.day || (reward.day === 7 && checkIn.consecutive % 7 === 0);
            
            return (
              <div 
                key={reward.day} 
                className={`flex items-center p-2 rounded-lg ${
                  isCompleted 
                    ? 'bg-green-100' 
                    : isCurrent 
                      ? 'bg-yellow-50 border border-yellow-200' 
                      : 'bg-gray-50'
                }`}
              >
                <div className={`w-8 h-8 rounded-full flex items-center justify-center mr-3 ${
                  isCompleted ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600'
                }`}>
                  {isCompleted ? <Icon icon="zi-check" /> : reward.day}
                </div>
                <div className="flex-1">
                  <div className="font-medium">Ngày {reward.day}</div>
                  <div className={`text-sm ${isCompleted ? 'text-green-600' : 'text-gray-500'}`}>
                    {reward.name}
                  </div>
                </div>
                {isCompleted && (
                  <div className="text-xs bg-green-500 text-white px-2 py-1 rounded">
                    Đã nhận
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>
      
      {/* Check-in button */}
      <Button 
        className={`w-full h-14 rounded-lg text-lg font-bold ${
          canCheckInToday 
            ? 'bg-gradient-to-r from-green-500 to-teal-500' 
            : 'bg-gray-300'
        }`}
        disabled={!canCheckInToday}
        onClick={handleCheckIn}
      >
        {canCheckInToday 
          ? 'Điểm danh ngay' 
          : `Đã điểm danh hôm nay (${format(new Date(), 'dd/MM')})`
        }
      </Button>
    </div>
  );
} 