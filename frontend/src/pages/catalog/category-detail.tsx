import HorizontalDivider from "@/components/horizontal-divider";
import ProductGrid from "@/components/product-grid";
import { useAtomValue } from "jotai";
import { productsByCategoryState, productsState } from "@/state";
import CategorySlider from "@/components/category-slider";
import { Suspense } from "react";
import { ProductGridSkeleton } from "../search";
import { EmptyCategory } from "@/components/empty";
import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import { Product } from "@/types";
import { getProductsByCategory } from "@/api/productApi";
import ProductItem from "@/components/product-item";

export default function CategoryDetailPage() {
  const { id } = useParams<{ id: string }>();
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchProducts = async () => {
      if (!id) return;
      
      try {
        setLoading(true);
        const data = await getProductsByCategory(parseInt(id));
        setProducts(data);
      } catch (error) {
        console.error('Error fetching products by category:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, [id]);

  return (
    <div className="h-full flex flex-col">
      <div className="pt-2">
        <CategorySlider />
      </div>
      
      {loading ? (
        <div className="flex-1 grid grid-cols-2 gap-4 p-4 overflow-y-auto">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="bg-skeleton animate-pulse rounded-xl h-[200px]"></div>
          ))}
        </div>
      ) : products.length > 0 ? (
        <div className="flex-1 overflow-y-auto">
          <div className="grid grid-cols-2 gap-4 p-4 pb-16">
            {products.map((product) => (
              <ProductItem key={product.id} product={product} />
            ))}
          </div>
        </div>
      ) : (
        <div className="flex-1 overflow-y-auto">
          <EmptyCategory />
        </div>
      )}
    </div>
  );
}
