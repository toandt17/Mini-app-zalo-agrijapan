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
   
  }

  return (
    <div className="grid grid-cols-4 p-4 gap-x-2 gap-y-8 bg-section">
      {categories.map((category) => (
        <TransitionLink>
          
        </TransitionLink>
      ))}
    </div>
  );
}