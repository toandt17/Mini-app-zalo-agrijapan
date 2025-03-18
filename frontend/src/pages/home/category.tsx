import React, { useEffect, useState } from 'react';
import TransitionLink from "@/components/transition-link";
import { getTopCategories } from "@/api/categoryApi";

// Định nghĩa type Category nếu chưa có trong @/types
interface Category {
  id: number;
  name: string;
  image: string;
}

export default function CategoryList() {
  const [categories, setCategories] = useState<Category[]>([]);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const data = await getTopCategories();
        setCategories(data);
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    };

    fetchCategories();
  }, []);

  return (
    <div className="bg-white py-4 px-2">
      <div className="grid grid-cols-5 gap-2">
        {categories.map((category) => (
          <TransitionLink
            key={category.id}
            className="flex flex-col items-center justify-center space-y-2 cursor-pointer"
            to={`/category/${category.id}`}
          >
            <div className="w-16 h-16 rounded-full overflow-hidden border-2 border-green-100 flex items-center justify-center bg-green-50 p-1">
              <img
                src={`http://127.0.0.1:8000/storage/${category.image}`}
                className="w-full h-full object-cover rounded-full"
                alt={category.name}
              />
            </div>
            <div className="text-center text-xs font-medium text-gray-700 w-full line-clamp-2">
              {category.name}
            </div>
          </TransitionLink>
        ))}
      </div>
    </div>
  );
}
