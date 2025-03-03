import React, { useEffect, useState } from 'react';
import TransitionLink from "@/components/transition-link";
import { Category as CategoryType } from "@/types";
import { getTopCategories } from "@/api/categoryApi";

export default function CategoryList() {
  const [categories, setCategories] = useState<CategoryType[]>([]);

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
    <div
      className="bg-section grid gap-x-2 gap-y-4 py-2 px-4 overflow-x-auto"
      style={{
        gridTemplateColumns: `repeat(${Math.ceil(
          categories.length > 4 ? categories.length / 2 : categories.length
        )}, minmax(70px, 1fr))`,
      }}
    >
      {categories.map((category) => (
        <TransitionLink
          key={category.id}
          className="flex flex-col items-center space-y-1 flex-none overflow-hidden cursor-pointer mx-auto"
          to={`/category/${category.id}`}
        >
          <img
            src={`http://127.0.0.1:8000/storage/${category.image}`}
            className="w-12 h-12 object-cover rounded-full bg-skeleton"
            alt={category.name}
          />
          <div className="text-center text-3xs w-full line-clamp-2 text-subtitle">
            {category.name}
          </div>
        </TransitionLink>
      ))}
    </div>
  );
}
