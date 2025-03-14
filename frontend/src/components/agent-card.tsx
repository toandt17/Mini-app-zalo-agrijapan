import React from 'react';
import { Box, Text } from 'zmp-ui';
import { MapPin, Phone } from 'lucide-react';
import { Agent } from '@/types';

interface AgentCardProps {
  agent: Agent;
}

const AgentCard: React.FC<AgentCardProps> = ({ agent }) => {
  const getFullAddress = () => {
    // Nếu có full_address từ API, sử dụng nó
    if (agent.full_address) {
      return agent.full_address;
    }
    
    // Nếu không, tự tạo từ các thành phần
    const parts = [];
    if (agent.address) parts.push(agent.address);
    if (agent.ward?.name) parts.push(agent.ward.name);
    if (agent.district?.name) parts.push(agent.district.name);
    if (agent.province?.name) parts.push(agent.province.name);
    return parts.join(', ');
  };

  return (
    <Box className="border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
      <Text.Title level={5} className="mb-2">{agent.name}</Text.Title>
      
      <Box className="flex items-start mb-2">
        <MapPin className="text-gray-500 mr-2 flex-shrink-0 mt-1" size={16} />
        <Text className="text-gray-700">{getFullAddress()}</Text>
      </Box>
      
      <Box className="flex items-center">
        <Phone className="text-gray-500 mr-2 flex-shrink-0" size={16} />
        <Text className="text-gray-700">{agent.phone}</Text>
      </Box>
    </Box>
  );
};

export default AgentCard; 