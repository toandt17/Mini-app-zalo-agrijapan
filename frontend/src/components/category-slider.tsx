import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import TransitionLink from "./transition-link";
import { Category } from "@/types";
import { getTopCategories } from "@/api/categoryApi";

export default function CategorySlider() {
  const { id } = useParams();
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchTopCategories = async () => {
      try {
        setLoading(true);
        const data = await getTopCategories();
        setCategories(data);
      } catch (error) {
        console.error('Error fetching top categories:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchTopCategories();
  }, []);

  if (loading && categories.length === 0) {
    return (
      <div className="px-3 py-2 overflow-x-auto flex space-x-2">
        <div className="h-8 w-24 flex-none rounded-full bg-skeleton animate-pulse"></div>
        <div className="h-8 w-24 flex-none rounded-full bg-skeleton animate-pulse"></div>
        <div className="h-8 w-24 flex-none rounded-full bg-skeleton animate-pulse"></div>
      </div>
    );
  }

  return (
    <div className="px-3 py-2 overflow-x-auto flex space-x-2">
      {categories.map((category) => (
        <TransitionLink
          to={`/category/${category.id}`}
          key={category.id}
          className={"h-8 flex-none rounded-full p-1 pr-2 flex items-center space-x-1 border border-black/15 ".concat(
            String(category.id) === id
              ? "bg-primary text-primaryForeground"
              : "bg-section"
          )}
        >
          <img
            src={`http://127.0.0.1:8000/storage/${category.image}`}
            className="w-6 h-6 rounded-full bg-skeleton"
            alt={category.name}
          />
          <p className="text-xs whitespace-nowrap">{category.name}</p>
        </TransitionLink>
      ))}
    </div>
  );
}
