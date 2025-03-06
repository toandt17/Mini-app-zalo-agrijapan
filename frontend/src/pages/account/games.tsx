import React from 'react';
import { useAtomValue } from 'jotai';
import { gameHistoryState } from '@/state';
import { format } from 'date-fns';
import { vi } from 'date-fns/locale';
import { Icon } from 'zmp-ui';

export default function GameHistoryPage() {
  const gameHistory = useAtomValue(gameHistoryState);
  
  const getActivityIcon = (type: string) => {
    switch(type) {
      case 'spin':
        return <Icon icon="zi-refresh" className="text-yellow-500" />;
      case 'checkin':
        return <Icon icon="zi-calendar" className="text-green-500" />;
      case 'quiz':
        return <Icon icon="zi-lightbulb" className="text-blue-500" />;
      default:
        return <Icon icon="zi-star" className="text-gray-500" />;
    }
  };
  
  const getActivityTitle = (type: string) => {
    switch(type) {
      case 'spin':
        return 'Vòng quay may mắn';
      case 'checkin':
        return 'Điểm danh hàng ngày';
      case 'quiz':
        return 'Trả lời câu hỏi';
      default:
        return 'Hoạt động khác';
    }
  };
  
  return (
    <div className="flex flex-col p-4 h-full">
      <div className="bg-gradient-to-r from-purple-100 to-blue-100 w-full rounded-xl p-4 mb-6">
        <h2 className="text-xl font-bold text-blue-800">Lịch sử hoạt động</h2>
        <p className="text-blue-600 text-sm">Các phần thưởng bạn đã nhận</p>
      </div>
      
      {gameHistory.length === 0 ? (
        <div className="text-center p-8 bg-white rounded-xl shadow">
          <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <Icon icon="zi-history" className="text-gray-400 text-2xl" />
          </div>
          <p className="text-gray-500">Bạn chưa có hoạt động nào</p>
        </div>
      ) : (
        <div className="space-y-4">
          {gameHistory.map((activity) => (
            <div key={activity.id} className="bg-white rounded-xl p-4 shadow-md flex items-center">
              <div className="w-12 h-12 rounded-full flex items-center justify-center mr-4"
                   style={{ backgroundColor: activity.reward?.color ? `${activity.reward.color}20` : '#f3f4f6' }}>
                {getActivityIcon(activity.type)}
              </div>
              <div className="flex-1">
                <div className="flex justify-between">
                  <h3 className="font-bold text-gray-800">{getActivityTitle(activity.type)}</h3>
                  <span className="text-xs text-gray-500">
                    {format(new Date(activity.createdAt), 'dd/MM/yyyy HH:mm')}
                  </span>
                </div>
                <p className="text-sm text-gray-600">{activity.result}</p>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
} 