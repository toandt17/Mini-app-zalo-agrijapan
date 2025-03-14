import { useState, useEffect } from 'react';
import axios from 'axios';

export default function LuckyWheelPage() {
  const [gameData, setGameData] = useState([]);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchGame();
  }, []);

  const fetchGame = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/games/lucky_wheel');
      console.log("Game Data:", response.data);  
  
      if (response.data && Array.isArray(response.data.lucky_wheel)) {
        setGameData(response.data.lucky_wheel);
      } else {
        setError("API không trả về danh sách giải thưởng hợp lệ");
      }
    } catch (err) {
      console.error("Không thể lấy thông tin game:", err);
      setError("Lỗi kết nối API");
    }
  };
  

 return (
  <div>
    <h1>Danh sách Giải Thưởng</h1>

    {error ? (
      <p style={{ color: 'red' }}>{error}</p>
    ) : (
      <ul>
        {gameData.length > 0 ? (
          gameData.map((game) => (
            <li key={game.id}>{game.reward}</li>
          ))
        ) : (
          <p>Đang tải dữ liệu...</p>
        )}
      </ul>
    )}
  </div>
);

}
