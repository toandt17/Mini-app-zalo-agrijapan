import { useNavigate, useParams } from "react-router-dom";
import { Facebook, MessageCircle, Music, Globe } from "lucide-react";

function OrdersPage() {
  const { status } = useParams();
  const navigate = useNavigate();

  return (
    <div className="flex flex-col space-y-3 p-6">
      <button
        className="w-full flex items-center justify-center px-4 py-3 rounded-lg text-white font-semibold bg-blue-600 hover:bg-blue-700 transition"
        onClick={() => window.open("https://www.facebook.com/Agrijapan2016", "_blank")}
      >
        <Facebook className="w-5 h-5 mr-2" /> Facebook
      </button>
      <button
        className="w-full flex items-center justify-center px-4 py-3 rounded-lg text-white font-semibold bg-blue-500 hover:bg-blue-600 transition"
        onClick={() => window.open("https://zalo.me/4595954910489503839", "_blank")}
      >
        <MessageCircle className="w-5 h-5 mr-2" /> Zalo
      </button>
      <button
        className="w-full flex items-center justify-center px-4 py-3 rounded-lg text-white font-semibold bg-black hover:bg-gray-800 transition"
        onClick={() => window.open("https://www.tiktok.com/@agrijapan", "_blank")}
      >
        <Music className="w-5 h-5 mr-2" /> TikTok
      </button>
      <button
        className="w-full flex items-center justify-center px-4 py-3 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition"
        onClick={() => window.open("https://agrijapanvn.com/", "_blank")}
      >
        <Globe className="w-5 h-5 mr-2" /> Website
      </button>
    </div>
  );
}

export default OrdersPage;