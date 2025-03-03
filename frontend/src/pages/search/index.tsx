import ProductItem from "@/components/product-item";
import Section from "@/components/section";
import { ProductItemSkeleton } from "@/components/skeleton";
import { HTMLAttributes, Suspense, useEffect, useState } from "react";
import { keywordState } from "@/state";
import { EmptySearchResult } from "@/components/empty";
import { useAtomValue } from "jotai";
import { Product } from "@/types";
import { searchProducts } from "@/api/productApi";

// Custom ProductGrid component specifically for search results
function SearchProductGrid({ products }: { products: Product[] }) {
  return (
    <div className="grid grid-cols-2 gap-4 px-4 pb-16">
      {products.map((product) => (
        <ProductItem key={product.id} product={product} />
      ))}
    </div>
  );
}

export function SearchResult() {
  const keyword = useAtomValue(keywordState);
  const [searchResult, setSearchResult] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchSearchResults = async () => {
      if (!keyword || keyword.trim() === '') {
        setSearchResult([]);
        setLoading(false);
        return;
      }

      try {
        setLoading(true);
        console.log('Searching for:', keyword);
        const data = await searchProducts(keyword);
        console.log('Got search results:', data);
        setSearchResult(data);
      } catch (error) {
        console.error('Error searching products:', error);
        setSearchResult([]);
      } finally {
        setLoading(false);
      }
    };

    fetchSearchResults();
  }, [keyword]);

  if (loading) {
    return <SearchResultSkeleton />;
  }

  return (
    <div className="w-full h-full space-y-2 bg-background">
      <Section
        title={`Kết quả (${searchResult.length})`}
        className="h-full flex flex-col overflow-y-auto pb-16"
      >
        {searchResult.length ? (
          <SearchProductGrid products={searchResult} />
        ) : (
          <EmptySearchResult />
        )}
      </Section>
    </div>
  );
}

export function SearchResultSkeleton() {
  return (
    <Section title={`Kết quả`}>
      <ProductGridSkeleton />
    </Section>
  );
}

export function ProductGridSkeleton({
  className,
  ...props
}: HTMLAttributes<HTMLDivElement>) {
  return (
    <div
      className={"grid grid-cols-2 px-4 pt-2 pb-8 gap-4 ".concat(
        className ?? ""
      )}
      {...props}
    >
      <ProductItemSkeleton />
      <ProductItemSkeleton />
      <ProductItemSkeleton />
      <ProductItemSkeleton />
    </div>
  );
}

export function RecommendedProducts() {
  const [recommendedProducts, setRecommendedProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchRecommendedProducts = async () => {
      try {
        setLoading(true);
        // Đây bạn có thể dùng API riêng để lấy sản phẩm gợi ý hoặc dùng tạm API getProducts
        const response = await fetch('http://127.0.0.1:8000/admin/products');
        const data = await response.json();
        setRecommendedProducts(data.slice(0, 6)); // Lấy 6 sản phẩm đầu tiên làm gợi ý
      } catch (error) {
        console.error('Error fetching recommended products:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchRecommendedProducts();
  }, []);

  if (loading) {
    return (
      <Section title="Gợi ý sản phẩm">
        <div className="py-2 px-4 pb-6 flex space-x-2 overflow-x-auto">
          {[...Array(4)].map((_, i) => (
            <div
              key={i}
              className="flex-none bg-skeleton animate-pulse rounded-xl"
              style={{ flexBasis: "calc((100vw - 48px) / 2)", height: "200px" }}
            ></div>
          ))}
        </div>
      </Section>
    );
  }

  return (
    <Section title="Gợi ý sản phẩm">
      <div className="py-2 px-4 pb-6 flex space-x-2 overflow-x-auto">
        {recommendedProducts.map((product) => (
          <div
            key={product.id}
            className="flex-none"
            style={{ flexBasis: "calc((100vw - 48px) / 2)" }}
          >
            <ProductItem product={product} />
          </div>
        ))}
      </div>
    </Section>
  );
}

export default function SearchPage() {
  const keyword = useAtomValue(keywordState);

  if (keyword) {
    return (
      <Suspense fallback={<SearchResultSkeleton />}>
        <SearchResult />
      </Suspense>
    );
  }
  return <RecommendedProducts />;
}
