import React from 'react';
import { Reward } from '@/types';

interface RewardCardProps {
  reward: Reward;
  showValue?: boolean;
  className?: string;
}

export default function RewardCard({ reward, showValue = true, className = '' }: RewardCardProps) {
  const getRewardIcon = () => {
    switch(reward.type) {
      case 'voucher':
        return '🎫';
      case 'point':
        return '🔮';
      case 'product':
        return '🎁';
      default:
        return '✨';
    }
  };
  
  const getRewardValueText = () => {
    switch(reward.type) {
      case 'voucher':
        return `${reward.value.toLocaleString()}đ`;
      case 'point':
        return `${reward.value} điểm`;
      case 'product':
        return 'Sản phẩm';
      default:
        return '';
    }
  };
  
  return (
    <div className={`bg-white rounded-xl p-4 shadow-md border border-gray-100 ${className}`}>
      <div className="flex items-center">
        <div 
          className="w-10 h-10 rounded-full flex items-center justify-center mr-3 text-xl"
          style={{ backgroundColor: `${reward.color}30` }}
        >
          {getRewardIcon()}
        </div>
        <div>
          <h4 className="font-bold text-gray-800">{reward.name}</h4>
          {showValue && reward.type !== 'none' && (
            <p className="text-xs text-gray-500">{getRewardValueText()}</p>
          )}
        </div>
      </div>
    </div>
  );
} 