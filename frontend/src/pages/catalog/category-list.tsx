import TransitionLink from "@/components/transition-link";
import { useState, useEffect } from "react";
import { Category } from "@/types";
import { getCategories } from "@/api/categoryApi";

export default function CategoryListPage() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        setLoading(true);
        const data = await getCategories();
        setCategories(data);
      } catch (error) {
        console.error('Error fetching categories:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchCategories();
  }, []);

  if (loading && categories.length === 0) {
    return (
      <div className="grid grid-cols-4 p-4 gap-x-2 gap-y-8 bg-section">
        {[...Array(8)].map((_, i) => (
          <div key={i} className="flex flex-col items-center space-y-1">
            <div className="w-full aspect-square rounded-full bg-skeleton animate-pulse"></div>
            <div className="h-4 w-3/4 bg-skeleton animate-pulse rounded"></div>
          </div>
        ))}
      </div>
    );
  }

  return (
    <div className="grid grid-cols-4 p-4 gap-x-2 gap-y-8 bg-section">
      {categories.map((category) => (
        <TransitionLink
          key={category.id}
          className="flex flex-col items-center space-y-1 overflow-hidden cursor-pointer"
          to={`/category/${category.id}`}
        >
          <div className="px-1">
            <img
              src={`http://127.0.0.1:8000/storage/${category.image}`}
              className="aspect-square object-cover rounded-full bg-skeleton"
              alt={category.name}
            />
          </div>
          <div className="text-center text-sm w-full line-clamp-2 text-subtitle">
            {category.name}
          </div>
        </TransitionLink>
      ))}
    </div>
  );
}
