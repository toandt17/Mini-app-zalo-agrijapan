import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import { Product } from "@/types";
import { formatPrice } from "@/utils/format";
import { useAddToCart } from "@/hooks";
import { Button } from "zmp-ui";
import Section from "@/components/section";
import ShareButton from "./share-buttont";
import RelatedProducts from "./related-products";
import HorizontalDivider from "@/components/horizontal-divider";

export default function ProductDetailPage() {
  const { id } = useParams<{ id: string }>(); // Get the product ID from the URL
  const [products, setProducts] = useState<Product[]>([]); // Store all products
  const [product, setProduct] = useState<Product | null>(null); // Current product
  const [loading, setLoading] = useState(true);

  // Call useAddToCart unconditionally with a default product
  const { addToCart } = useAddToCart(product || { id: 0, name: '', price: 0, image: '' });

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response = await fetch(`http://127.0.0.1:8000/admin/products`);
        if (!response.ok) throw new Error('Failed to fetch');
        const data = await response.json();
        setProducts(data);
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  useEffect(() => {
    if (products.length > 0) {
      const foundProduct = products.find((p) => p.id === parseInt(id || "", 10));
      setProduct(foundProduct || null);
    }
  }, [products, id]);

  if (loading) {
    return <div className="p-4">Đang tải...</div>;
  }

  if (!product) {
    return <div className="p-4">Không tìm thấy sản phẩm</div>;
  }

  return (
    <div className="w-full h-full flex flex-col">
      <div className="flex-1 overflow-y-auto">
        <div className="w-full p-4 pb-2 space-y-4 bg-section">
          <img
            src={product.image}
            alt={product.name}
            className="w-full h-full object-cover rounded-lg"
            style={{
              viewTransitionName: `product-image-${product.id}`,
            }}
          />
          <div>
            <div className="text-xl font-bold text-primary">
              {formatPrice(product.price.toString())}
            </div>
            {product.original_price && (
              <div className="text-2xs space-x-0.5">
                <span className="text-subtitle line-through">
                  {formatPrice(product.original_price.toString())}
                </span>
                <span className="text-danger">
                  -{100 - Math.round((parseFloat(product.price) * 100) / parseFloat(product.original_price))}%
                </span>
              </div>
            )}
            <div className="text-sm mt-1">{product.name}</div>
          </div>
          <ShareButton product={product} />
        </div>

        {product.detail && (
          <>
            <div className="bg-background h-2 w-full"></div>
            <Section title="Mô tả sản phẩm">
              <div className="text-sm whitespace-pre-wrap text-subtitle p-4 pt-2">
                {product.detail}
              </div>
            </Section>
          </>
        )}
        <div className="bg-background h-2 w-full"></div>
        <Section title="Sản phẩm khác">
          <RelatedProducts currentProductId={product.id} />
        </Section>
      </div>

      <HorizontalDivider />
      <div className="flex-none grid grid-cols-2 gap-2 py-3 px-4 bg-section">
        <Button
          variant="tertiary"
          onClick={() => {
            if (product) {
              addToCart(1, {
                toast: true,
              });
            }
          }}
        >
          Thêm vào giỏ
        </Button>
        <Button
          onClick={() => {
            if (product) {
              addToCart(1);
            }
          }}
        >
          Mua ngay
        </Button>
      </div>
    </div>
  );
}