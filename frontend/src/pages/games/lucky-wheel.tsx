import React, { useState, useEffect } from "react";
import { useAtom } from "jotai";
import { luckyWheelState, gameHistoryState } from "@/state";
import axios from "axios";
import { Button } from "zmp-ui";
import confetti from "canvas-confetti";
// Hiệu ứng trúng thưởng 🎉
const triggerConfetti = () => {
  confetti({
    particleCount: 100,
    spread: 70,
    origin: { y: 0.6 },
  });
};

export default function LuckyWheelPage() {
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  const [gameHistory, setGameHistory] = useAtom(gameHistoryState);
  const [isSpinning, setIsSpinning] = useState(false);
  const [result, setResult] = useState(null);
  const [rotation, setRotation] = useState(0);
  const [error, setError] = useState(null);

  const colors = ["#FF8A65", "#4CAF50", "#FFC107", "#3F51B5", "#E91E63", "#009688"];
  
  useEffect(() => {
    fetchGame();
  }, []);

  const fetchGame = async () => {
    try {
      const response = await axios.get("http://127.0.0.1:8000/games/lucky_wheel");
      console.log("Game Data:", response.data);

      if (response.data && Array.isArray(response.data.lucky_wheel)) {
        setLuckyWheel((prev) => ({
          ...prev,
          rewards: response.data.lucky_wheel,
        }));
      }
    } catch (err) {
      console.error("Lỗi khi gọi API:", err);
    }
  };

  const startSpin = () => {
    if (isSpinning || luckyWheel.remainingSpins <= 0) return;

    setIsSpinning(true);
    setResult(null);

    const randomIndex = Math.floor(Math.random() * luckyWheel.rewards.length);
    const selectedReward = luckyWheel.rewards[randomIndex];

    const segmentSize = 360 / luckyWheel.rewards.length;
    const targetRotation = 360 * 5 + (360 - (randomIndex * segmentSize + segmentSize / 2));

    setRotation((prevRotation) => prevRotation + targetRotation);

    setTimeout(() => {
      setIsSpinning(false);
      setResult(selectedReward);
      setLuckyWheel((prev) => ({ ...prev, remainingSpins: prev.remainingSpins - 1 }));
      setGameHistory((prev) => [{ id: Date.now(), type: "spin", result: selectedReward.reward }, ...prev]);
      if (selectedReward.reward !== "Chúc may mắn!") triggerConfetti();
    }, 5000);
  };
  const splitText = (text, maxWordsPerLine = 3) => {
    const words = text.split(" ");
    let lines = [];
    for (let i = 0; i < words.length; i += maxWordsPerLine) {
      lines.push(words.slice(i, i + maxWordsPerLine).join(" "));
    }
    return lines;
  };
  const generateWheelSegments = () => {
    if (!luckyWheel.rewards || luckyWheel.rewards.length === 0) return null;
    const totalRewards = luckyWheel.rewards.length;
    const segmentAngle = 360 / totalRewards;
    const radius = 150;
    const centerX = 200;
    const centerY = 200;
  
    return luckyWheel.rewards.map((reward, i) => {
      const color = colors[i % colors.length];
      const startAngle = i * segmentAngle;
      const endAngle = (i + 1) * segmentAngle;
      const startRad = ((startAngle - 90) * Math.PI) / 180;
      const endRad = ((endAngle - 90) * Math.PI) / 180;
      const x1 = centerX + radius * Math.cos(startRad);
      const y1 = centerY + radius * Math.sin(startRad);
      const x2 = centerX + radius * Math.cos(endRad);
      const y2 = centerY + radius * Math.sin(endRad);
      const path = `M ${centerX} ${centerY} L ${x1} ${y1} A ${radius} ${radius} 0 0 1 ${x2} ${y2} Z`;
  
      const textRadius = radius - 30;
      const lines = splitText(reward.reward);
  
      return (
        <g key={reward.id}>
          <path d={path} fill={color} stroke="white" strokeWidth="2" />
          {lines.map((line, index) => {
            const lineRadius = textRadius - index * 15; // Mỗi dòng lùi vào một chút
            const textX = centerX + lineRadius * Math.cos((startRad + endRad) / 2);
            const textY = centerY + lineRadius * Math.sin((startRad + endRad) / 2);
            return (
              <text
                key={index}
                x={textX}
                y={textY}
                fill="white"
                fontSize="14px"
                fontWeight="bold"
                textAnchor="middle"
                transform={`rotate(${startAngle + segmentAngle / 2}, ${textX}, ${textY})`}
              >
                {line}
              </text>
            );
          })}
          <image
            href=""
            x={centerX - 20}
            y={centerY - textRadius + lines.length * 15 + 10}
            width="40"
            height="40"
            transform={`rotate(${startAngle + segmentAngle / 2}, ${centerX}, ${centerY})`}
          />
        </g>
      );
    });
  };

  return (
    <div className="flex flex-col items-center p-4 h-full">
      <h2 className="text-xl font-bold text-indigo-800 mb-2">🎡 Vòng Quay May Mắn</h2>
      <p className="text-indigo-600">Bạn còn <span className="font-bold text-xl">{luckyWheel.remainingSpins}</span> lượt quay</p>
      <div className="relative w-full max-w-xs mx-auto my-6">
        <div className="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
          <div className="w-0 h-0 border-l-[15px] border-r-[15px] border-t-[25px] border-l-transparent border-r-transparent border-t-red-500"></div>
        </div>
        <div className="relative w-full aspect-square">
          <svg
            viewBox="0 0 400 400"
            className="w-full h-full"
            style={{ transform: `rotate(${rotation}deg)`, transition: "transform 5s cubic-bezier(0.25, 1, 0.5, 1)" }}
          >
            <g>{generateWheelSegments()}</g>
            <circle cx="200" cy="200" r="40" fill="#2196F3" stroke="white" strokeWidth="2" />
          </svg>
          <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white font-bold text-lg">
            Quay
          </div>
          {luckyWheel.remainingSpins <= 0 && (
              <p className="text-red-500 font-bold mt-2">Bạn đã hết lượt quay! Hãy quay lại sau.</p>
            )}

        </div>
      </div>
      {result && <div className="p-4 mb-6 rounded-xl text-center bg-yellow-100"><h3 className="text-lg font-bold">🎊 Chúc mừng bạn đã nhận được:</h3><p className="text-yellow-700">{result.reward}</p></div>}
      <Button className={`w-40 h-14 rounded-full text-lg font-bold shadow-md ${isSpinning || luckyWheel.remainingSpins <= 0 ? "bg-gray-300" : "bg-gradient-to-r from-indigo-500 to-purple-600"}`} disabled={isSpinning || luckyWheel.remainingSpins <= 0} onClick={startSpin}>
        {isSpinning ? "Đang quay..." : luckyWheel.remainingSpins <= 0 ? "Hết lượt" : "Quay ngay"}
      </Button>
      
      <div className="mt-6 text-center">
        <h3 className="text-lg font-bold text-indigo-800">🎁 Cách nhận thêm lượt quay</h3>
        <p className="text-indigo-600 mb-2">Điểm danh hằng ngày để nhận thêm lượt quay miễn phí!</p>
        <div className="flex justify-center gap-4">
          <Button
            className="w-40 h-12 rounded-full bg-green-500 text-white font-bold shadow-md hover:bg-green-600"
            onClick={() => window.location.href = "/games/check-in"}
          >
            Điểm danh ngay
          </Button>
          <Button
            className="w-40 h-12 rounded-full bg-blue-500 text-white font-bold shadow-md hover:bg-blue-600"
            onClick={() => window.location.href = "/games/tasks"}
          >
            Làm nhiệm vụ
          </Button>
        </div>
      </div>
    </div>
    
  );
}
