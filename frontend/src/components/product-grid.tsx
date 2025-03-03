import { useEffect, useState } from "react";
import { Product } from "@/types";
import ProductItem from "./product-item";
import { HTMLAttributes } from "react";
import { getProducts } from "@/api/productApi";

export interface ProductGridProps extends HTMLAttributes<HTMLDivElement> {
  replace?: boolean;
}

export default function ProductGrid({
  className,
  replace,
  ...props
}: ProductGridProps) {
  const [products, setProducts] = useState<Product[]>([]);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const data = await getProducts();
        setProducts(data);
      } catch (error) {
        console.error('Error fetching products:', error);
      }
    };

    fetchProducts();
  }, []);

  return (
    <div
      className={"grid grid-cols-2 px-4 pt-2 pb-8 gap-4 ".concat(
        className ?? ""
      )}
      {...props}
    >
      {products.map((product) => (
        <ProductItem key={product.id} product={product} replace={replace} />
      ))}
    </div>
  );
}