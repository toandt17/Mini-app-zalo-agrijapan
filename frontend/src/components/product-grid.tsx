import { useEffect, useState } from "react";
import { Product } from "@/types";
import ProductItem from "./product-item";
import { HTMLAttributes } from "react";

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
    // Gọi API để lấy danh sách sản phẩm
    fetch('http://127.0.0.1:8000/admin/products') // Đảm bảo URL này trỏ đến server Laravel
      .then(response => response.json())
      .then(data => {
        console.log('Fetched products:', data); // Log dữ liệu sản phẩm
        setProducts(data);
      })
      .catch(error => console.error('Error fetching products:', error));
  }, []); // Mảng phụ thuộc rỗng để chỉ gọi API một lần khi component được mount

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