import { useState, useEffect } from "react";
import { getUserInfo } from "zmp-sdk";
import { Bar } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import { saveUser, getUserPointTransactions, getUserPointsStatistics } from "@/api/gameApi";

// Đăng ký các thành phần cần thiết cho Chart.js
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);

export default function PointsPage() {
  const [userData, setUserData] = useState({
    id: 0,
    name: "",
    avatar: "",
    points: 0,
    zaloId: ""
  });

  const [transactionHistory, setTransactionHistory] = useState([]);
  const [loading, setLoading] = useState(true);
  const [chartData, setChartData] = useState({
    labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
    datasets: [
      {
        label: 'Điểm tích lũy',
        data: [0, 0, 0, 0, 0, 0, 0],
        backgroundColor: 'rgba(34, 197, 94, 0.6)',
        borderColor: 'rgb(34, 197, 94)',
        borderWidth: 1,
        borderRadius: 5,
      },
    ],
  });

  const chartOptions = {
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
      title: {
        display: false,
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          display: true,
          color: 'rgba(0, 0, 0, 0.05)',
        },
      },
      x: {
        grid: {
          display: false,
        },
      },
    },
  };

  useEffect(() => {
    // Lấy thông tin người dùng và dữ liệu điểm từ API
    const fetchData = async () => {
      setLoading(true);
      try {
        // Kiểm tra nếu đang chạy trong dev mode
        const isDev = !window.ZJSBridge;
        
        if (isDev) {
          // Sử dụng dữ liệu mẫu trong dev mode
          const mockUser = {
            id: 1,
            name: "Người dùng (Dev)",
            avatar: "",
            points: 250,
            zaloId: "dev_user"
          };
          setUserData(mockUser);
          
          // Giả lập dữ liệu giao dịch điểm
          setTransactionHistory([
            { id: 1, date: "20/10/2023", activity_type: "Vòng quay may mắn", points: 50, type: "earn" },
            { id: 2, date: "18/10/2023", activity_type: "Điểm danh hàng ngày", points: 10, type: "earn" },
            { id: 3, date: "15/10/2023", activity_type: "Đổi quà tặng", points: -100, type: "spend" },
            { id: 4, date: "10/10/2023", activity_type: "Hoàn thành trả lời câu hỏi", points: 30, type: "earn" },
            { id: 5, date: "05/10/2023", activity_type: "Điểm danh hàng ngày", points: 10, type: "earn" },
          ]);
          
          // Giả lập dữ liệu chart
          setChartData({
            labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
            datasets: [
              {
                label: 'Điểm tích lũy',
                data: [20, 35, 10, 50, 30, 15, 40],
                backgroundColor: 'rgba(34, 197, 94, 0.6)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1,
                borderRadius: 5,
              },
            ],
          });
        } else {
          // Production mode - lấy từ Zalo SDK
          const result = await getUserInfo({});
          if (result && result.userInfo) {
            // Lưu thông tin người dùng vào hệ thống
            const saveResult = await saveUser({
              zaloId: result.userInfo.id,
              name: result.userInfo.name || "",
              avatar: result.userInfo.avatar || "",
              idByOA: result.userInfo.idByOA || "",
              followedOA: result.userInfo.followedOA || false,
              isSensitive: result.userInfo.isSensitive || false
            });
            
            if (saveResult.success && saveResult.user) {
              const user = {
                id: saveResult.user.id,
                name: saveResult.user.name || "Người dùng",
                avatar: saveResult.user.avatar || "",
                points: saveResult.user.points || 0,
                zaloId: result.userInfo.id
              };
              setUserData(user);
              
              // Lấy lịch sử giao dịch điểm
              const transactionsResult = await getUserPointTransactions(user.id);
              if (transactionsResult.success && transactionsResult.data) {
                setTransactionHistory(transactionsResult.data.transactions || []);
              }
              
              // Lấy thống kê điểm theo ngày
              const statisticsResult = await getUserPointsStatistics(user.id);
              if (statisticsResult.success && statisticsResult.data) {
                setChartData({
                  labels: statisticsResult.data.labels,
                  datasets: [
                    {
                      label: 'Điểm tích lũy',
                      data: statisticsResult.data.data,
                      backgroundColor: 'rgba(34, 197, 94, 0.6)',
                      borderColor: 'rgb(34, 197, 94)',
                      borderWidth: 1,
                      borderRadius: 5,
                    },
                  ],
                });
              }
            }
          }
        }
      } catch (error) {
        console.error("Lỗi khi lấy dữ liệu:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  // Danh sách các cách thức tích điểm
  const pointEarningMethods = [
    { 
      title: "Vòng quay may mắn", 
      description: "Tham gia vòng quay mỗi ngày để tích điểm", 
      points: "10-50 điểm",
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><circle cx="12" cy="12" r="10"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10h-5.8"/></svg>`
    },
    { 
      title: "Điểm danh hàng ngày", 
      description: "Điểm danh mỗi ngày để nhận điểm thưởng", 
      points: "10 điểm/ngày",
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M9 16l2 2 4-4"/></svg>`
    },
    { 
      title: "Trả lời câu hỏi", 
      description: "Hoàn thành các câu hỏi để tích điểm", 
      points: "5-30 điểm",
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`
    },
    { 
      title: "Mua sắm", 
      description: "Nhận điểm khi mua sản phẩm", 
      points: "1 điểm/10,000đ",
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>`
    }
  ];

  // Hàm chuyển đổi loại hoạt động thành mô tả người dùng
  const formatActivityType = (type) => {
    const activityMap = {
      'lucky_wheel': 'Vòng quay may mắn',
      'daily_checkin': 'Điểm danh hàng ngày',
      'quiz_completion': 'Trả lời câu hỏi',
      'gift_redemption': 'Đổi quà tặng',
      'purchase': 'Mua sắm',
      'admin_adjustment': 'Điều chỉnh bởi quản trị viên'
    };
    
    return activityMap[type] || type;
  };

  return (
    <div className="flex flex-col bg-gray-50 min-h-screen">
      {/* Header hiển thị điểm */}
      <div className="bg-gradient-to-br from-green-500 to-green-700 text-white px-4 pt-6 pb-10 relative overflow-hidden">
        {/* Pattern overlay cho header */}
        <div className="absolute inset-0 opacity-10">
          <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
              <pattern id="pattern" width="8" height="8" patternUnits="userSpaceOnUse">
                <circle cx="1" cy="1" r="1" fill="white" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#pattern)" />
          </svg>
        </div>
        
        {/* Thông tin tích điểm */}
        <div className="text-center relative z-10">
          <h1 className="text-2xl font-bold">Điểm tích lũy của bạn</h1>
          <div className="mt-4 flex items-center justify-center">
            <div className="w-24 h-24 rounded-full bg-white shadow-lg flex items-center justify-center">
              <span className="text-green-600 text-3xl font-bold">{userData.points}</span>
            </div>
          </div>
          <p className="mt-3 text-sm opacity-90">Tích lũy điểm để đổi lấy quà tặng hấp dẫn</p>
        </div>
      </div>

      {/* Biểu đồ thống kê điểm */}
      <div className="px-4 -mt-5 mb-6 relative z-20">
        <div className="bg-white rounded-xl shadow-lg p-4">
          <h3 className="text-base font-medium text-gray-800 mb-3">Điểm tích lũy trong tuần</h3>
          {loading ? (
            <div className="h-48 flex items-center justify-center">
              <div className="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-green-500"></div>
            </div>
          ) : (
            <div className="h-48">
              <Bar data={chartData} options={chartOptions} />
            </div>
          )}
        </div>
      </div>

      {/* Cách thức tích điểm */}
      <div className="px-4 mb-6">
        <h3 className="text-lg font-semibold text-gray-800 mb-3">Cách thức tích điểm</h3>
        <div className="bg-white rounded-xl shadow-sm overflow-hidden">
          {pointEarningMethods.map((method, index) => (
            <div 
              key={index} 
              className="flex items-start py-4 px-4 border-b border-gray-100 last:border-b-0"
            >
              <div className="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 mt-1">
                <span dangerouslySetInnerHTML={{ __html: method.icon }}></span>
              </div>
              <div className="ml-3 flex-1">
                <div className="flex justify-between items-center">
                  <h4 className="font-medium text-gray-800">{method.title}</h4>
                  <span className="text-sm text-green-600 font-medium bg-green-50 px-2 py-1 rounded-full">
                    {method.points}
                  </span>
                </div>
                <p className="text-sm text-gray-600 mt-1">{method.description}</p>
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* Lịch sử giao dịch điểm */}
      <div className="px-4 mb-8">
        <h3 className="text-lg font-semibold text-gray-800 mb-3">Lịch sử giao dịch điểm</h3>
        {loading ? (
          <div className="bg-white rounded-xl p-8 flex justify-center">
            <div className="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-green-500"></div>
          </div>
        ) : transactionHistory.length > 0 ? (
          <div className="bg-white rounded-xl shadow-sm overflow-hidden divide-y divide-gray-100">
            {transactionHistory.map((transaction) => (
              <div 
                key={transaction.id} 
                className="flex items-center justify-between py-3 px-4"
              >
                <div>
                  <h4 className="font-medium text-gray-800">{formatActivityType(transaction.activity_type)}</h4>
                  <p className="text-xs text-gray-500 mt-1">{transaction.date}</p>
                </div>
                <div className={`flex items-center ${transaction.type === 'earn' || parseInt(transaction.points) > 0 ? 'text-green-600' : 'text-red-500'}`}>
                  <span className="text-lg font-semibold mr-1">
                    {transaction.type === 'earn' || parseInt(transaction.points) > 0 ? '+' : ''}
                  </span>
                  <span className="font-medium">{transaction.points}</span>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="bg-white rounded-xl p-8 text-center">
            <div className="text-gray-500">Chưa có giao dịch điểm nào</div>
          </div>
        )}
      </div>
    </div>
  );
}
