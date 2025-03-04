import Banners from "./banners";
import Category from "./category";
import FlashSales from "./flash-sales";
import LuckyWheel from "./LuckyWheel"; // Import vòng quay may mắn


const HomePage: React.FunctionComponent = () => {
  return (
    <div className="min-h-full space-y-2 py-2">
      <Category />
      <div className="bg-section">
        <Banners />
      </div>
      <LuckyWheel /> {/* Thêm vòng quay may mắn vào trang chủ */}
      <FlashSales />
    </div>
  );
};

export default HomePage;
