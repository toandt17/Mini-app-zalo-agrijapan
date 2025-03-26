import React, { useEffect } from 'react';
import { useAtomValue } from 'jotai';
import { luckyWheelState, checkInState, missionsState } from '@/state';
import TransitionLink from '@/components/transition-link';
import { Icon } from 'zmp-ui';
import { format } from 'date-fns';
import { vi } from 'date-fns/locale';

export default function GamesPage() {
  const luckyWheel = useAtomValue(luckyWheelState);
  const checkIn = useAtomValue(checkInState);
  const missions = useAtomValue(missionsState);
  
  // Check if user can check in today
  const canCheckInToday = !checkIn.lastCheckIn || new Date(checkIn.lastCheckIn).toDateString() !== new Date().toDateString();
  
  // Count completed missions
  const completedMissions = missions.filter(mission => mission.status === 'completed').length;
  
  return (
    <div className="flex flex-col space-y-4 p-4">
      <div className="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg">
        <h2 className="text-xl font-bold mb-2">Mini Games</h2>
        <p className="text-white/80">Tham gia các hoạt động để nhận quà!</p>
      </div>
      
      {/* Lucky Wheel Game */}
      <TransitionLink 
        to="/games/lucky-wheel" 
        className="bg-white rounded-xl p-4 shadow-md flex items-center"
      >
        <div className="h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
          <Icon icon="zi-star" className="text-yellow-500" />
        </div>
        <div className="flex-1">
          <h3 className="font-bold text-gray-800">Vòng Quay May Mắn</h3>
          <p className="text-xs text-gray-500">Quay để nhận quà giá trị</p>
        </div>
        <div className="flex flex-col items-end">
          <span className="text-lg font-bold text-yellow-500">{luckyWheel.remainingSpins || 0}</span>
          <span className="text-xs text-gray-500">lượt quay</span>
        </div>
      </TransitionLink>
      
      {/* Check-in Game */}
      <TransitionLink 
        to="/games/check-in" 
        className="bg-white rounded-xl p-4 shadow-md flex items-center"
      >
        <div className="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
          <Icon icon="zi-calendar" className="text-green-500" />
        </div>
        <div className="flex-1">
          <h3 className="font-bold text-gray-800">Điểm Danh Hàng Ngày</h3>
          <p className="text-xs text-gray-500">
            {canCheckInToday 
              ? "Điểm danh để nhận quà" 
              : "Bạn đã điểm danh hôm nay"}
          </p>
        </div>
        <div className="flex flex-col items-end">
          <span className="text-lg font-bold text-green-500">{checkIn.consecutive || 0}</span>
          <span className="text-xs text-gray-500">ngày liên tiếp</span>
        </div>
      </TransitionLink>
      
      {/* Mission Game */}
      <TransitionLink 
        to="/games/mission" 
        className="bg-white rounded-xl p-4 shadow-md flex items-center"
      >
        <div className="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
          <Icon icon="zi-star" className="text-purple-500" />
        </div>
        <div className="flex-1">
          <h3 className="font-bold text-gray-800">Nhiệm Vụ Hàng Ngày</h3>
          <p className="text-xs text-gray-500">Hoàn thành nhiệm vụ, nhận quà tặng</p>
        </div>
        <div className="flex flex-col items-end">
          <span className="text-lg font-bold text-purple-500">{completedMissions}</span>
          <span className="text-xs text-gray-500">đã hoàn thành</span>
        </div>
      </TransitionLink>
      
      {/* Quiz Game */}
      <TransitionLink 
        to="/games/quiz" 
        className="bg-white rounded-xl p-4 shadow-md flex items-center"
      >
        <div className="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
          <Icon icon="zi-star" className="text-blue-500" />
        </div>
        <div className="flex-1">
          <h3 className="font-bold text-gray-800">Trả Lời Câu Hỏi</h3>
          <p className="text-xs text-gray-500">Kiểm tra kiến thức & nhận quà</p>
        </div>
        <div className="flex items-center text-blue-500">
          <Icon icon="zi-chevron-right" />
        </div>
      </TransitionLink>
      
      {/* Game History */}
      <TransitionLink 
        to="/account/games" 
        className="bg-white rounded-xl p-4 shadow-md flex items-center"
      >
        <div className="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mr-4">
          <Icon icon="zi-star" className="text-gray-500" />
        </div>
        <div className="flex-1">
          <h3 className="font-bold text-gray-800">Lịch Sử Hoạt Động</h3>
          <p className="text-xs text-gray-500">Xem lại quà đã nhận</p>
        </div>
        <div className="flex items-center text-gray-500">
          <Icon icon="zi-chevron-right" />
        </div>
      </TransitionLink>
    </div>
  );
} 