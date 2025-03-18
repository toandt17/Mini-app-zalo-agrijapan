<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh mục 1: Quy Trình Cây Lúa
        $quyTrinhCayLua = [
            [
                'name' => 'Bộ Giải Pháp Chồi To Cây Khỏe',
                'status' => 'active',
                'image' => 'bo_giai_phap_choi_to_cay_khoe.jpg',
                'detail' => 'Giải pháp giúp cây lúa phát triển mạnh mẽ và khỏe mạnh, tạo chồi to khỏe.',
            ],
            [
                'name' => 'Bộ Giải Pháp Đòng Bự Bông Kẹo',
                'status' => 'active',
                'image' => 'bo_giai_phap_dong_bu_bong_keo.jpg',
                'detail' => 'Giải pháp giúp lúa đòng bự, bông kẹo, tăng năng suất.',
            ],
            [
                'name' => 'Bộ Giải Pháp Sạch Khuẩn Sáng Bông',
                'status' => 'active',
                'image' => 'bo_giai_phap_sach_khuan_sang_bong.jpg',
                'detail' => 'Giải pháp giúp lúa sạch khuẩn, sáng bông, chống bệnh.',
            ],
            [
                'name' => 'Bộ Giải Pháp Trừ Bệnh AgriJapan',
                'status' => 'active',
                'image' => 'bo_giai_phap_tru_benh_agrijapan.jpg',
                'detail' => 'Bộ giải pháp phòng và trị bệnh hiệu quả cho cây lúa.',
            ],
            [
                'name' => 'Bộ Giải Pháp Hoàn Hảo Tạo Hạt Thần Tốc',
                'status' => 'active',
                'image' => 'bo_giai_phap_hoan_hao_tao_hat_than_toc.jpg',
                'detail' => 'Giải pháp giúp quá trình tạo hạt nhanh chóng, hiệu quả.',
            ],
            [
                'name' => 'Bộ Giải Pháp Trừ Rầy AgriJapan',
                'status' => 'active',
                'image' => 'bo_giai_phap_tru_ray_agrijapan.jpg',
                'detail' => 'Bộ giải pháp trừ rầy hiệu quả, bảo vệ cây lúa.',
            ],
            [
                'name' => 'Bộ Giải Pháp Dinh Dưỡng Phục Hồi Siêu Tốc',
                'status' => 'active',
                'image' => 'bo_giai_phap_dinh_duong_phuc_hoi_sieu_toc.jpg',
                'detail' => 'Cung cấp dinh dưỡng và giúp cây lúa phục hồi nhanh chóng sau stress.',
            ],
            [
                'name' => 'Bộ Giải Pháp Tuyệt Chiêu Nấm Khuẩn',
                'status' => 'active',
                'image' => 'bo_giai_phap_tuyet_chieu_nam_khuan.jpg',
                'detail' => 'Giải pháp phòng và trị nấm khuẩn toàn diện cho cây lúa.',
            ],
            [
                'name' => 'Bộ Giải Pháp Trổ Thoát Kẹo Bông',
                'status' => 'active',
                'image' => 'bo_giai_phap_tro_thoat_keo_bong.jpg',
                'detail' => 'Giúp lúa trổ đều, thoát tốt và tạo bông kẹo.',
            ],
            [
                'name' => 'Bộ Giải Pháp Sạch Nấm Khuẩn Gốc',
                'status' => 'active',
                'image' => 'bo_giai_phap_sach_nam_khuan_goc.jpg',
                'detail' => 'Giải pháp diệt nấm khuẩn tận gốc, bảo vệ cây lúa toàn diện.',
            ],
        ];

        foreach ($quyTrinhCayLua as $product) {
            Product::create(array_merge($product, ['category_id' => 1]));
        }

        // Danh mục 2: Điều hòa sinh trưởng
        $dieuHoaSinhTruong = [
            [
                'name' => 'Điều Hòa Sinh Trưởng BRASS 481',
                'status' => 'active',
                'image' => 'dieu_hoa_sinh_truong_brass_481.jpg',
                'detail' => 'Sản phẩm giúp điều hòa sinh trưởng cho cây trồng, kích thích phát triển.',
            ],
            [
                'name' => 'Điều hòa sinh trưởng AgriJapan',
                'status' => 'active',
                'image' => 'dieu_hoa_sinh_truong_agrijapan.jpg',
                'detail' => 'Sản phẩm chất lượng cao giúp điều hòa sinh trưởng, tăng năng suất cây trồng.',
            ],
            [
                'name' => 'GIBBER 40WG – GABA CỐM',
                'status' => 'active',
                'image' => 'gibber_40wg_gaba_com.jpg',
                'detail' => 'Chất điều hòa sinh trưởng dạng cốm, kích thích tăng trưởng cây trồng.',
            ],
            [
                'name' => 'ACGABACYTO 50TB – GABA VIÊN',
                'status' => 'active',
                'image' => 'acgabacyto_50tb_gaba_vien.jpg',
                'detail' => 'Chất điều hòa sinh trưởng dạng viên, dễ sử dụng và hiệu quả cao.',
            ],
            [
                'name' => 'LK.GABACYTO',
                'status' => 'active',
                'image' => 'lk_gabacyto.jpg',
                'detail' => 'Sản phẩm điều hòa sinh trưởng chuyên dụng cho cây lúa và hoa màu.',
            ],
        ];

        foreach ($dieuHoaSinhTruong as $product) {
            Product::create(array_merge($product, ['category_id' => 2]));
        }

        // Danh mục 3: Phân bón siêu vi lượng
        $phanBonSieuViLuong = [
            [
                'name' => 'KẼM ARMOR, KẼM BÁC SĨ (LK-ZN ARMOR)',
                'status' => 'active',
                'image' => 'kem_armor_kem_bac_si.jpg',
                'detail' => 'Phân bón siêu vi lượng giúp cây trồng hấp thụ dinh dưỡng tốt hơn, bổ sung kẽm cho cây.',
            ],
            [
                'name' => 'KẼM XANH, KẼM ARMOR (LK-ZN ARMOR)',
                'status' => 'active',
                'image' => 'kem_xanh_kem_armor.jpg',
                'detail' => 'Phân bón vi lượng giàu kẽm, giúp cây xanh tốt và tăng sức đề kháng.',
            ],
            [
                'name' => 'AC-SUPERPOTAS (KALI SỮA 30%)',
                'status' => 'active',
                'image' => 'ac_superpotas_kali_sua_30.jpg',
                'detail' => 'Phân bón chứa Kali cao dạng sữa, giúp tăng cường quá trình tạo bông và hạt.',
            ],
            [
                'name' => 'KALI SỮA ÔNG GIÀ (LK-K-Ca)',
                'status' => 'active',
                'image' => 'kali_sua_ong_gia.jpg',
                'detail' => 'Phân bón Kali-Canxi dạng sữa, giúp củng cố thành tế bào và tăng độ cứng cho cây.',
            ],
            [
                'name' => 'AC-AMINO-BO (SỮA ĐẬM ĐẶC)',
                'status' => 'active',
                'image' => 'ac_amino_bo_sua_dam_dac.jpg',
                'detail' => 'Phân bón chứa Amino Acid và Bo giúp kích thích quá trình ra hoa đậu trái.',
            ],
            [
                'name' => 'ARIGOLD 620 (LÂN HỮU HIỆU HAI CHIỀU)',
                'status' => 'active',
                'image' => 'arigold_620_lan_huu_hieu_hai_chieu.jpg',
                'detail' => 'Phân bón lân hiệu quả cao, giúp kích thích bộ rễ phát triển mạnh mẽ.',
            ],
        ];

        foreach ($phanBonSieuViLuong as $product) {
            Product::create(array_merge($product, ['category_id' => 3]));
        }

        // Danh mục 4: Thuốc trừ bệnh
        $thuocTruBenh = [
            [
                'name' => 'STARSUPER 21SL Arigod',
                'status' => 'active',
                'image' => 'starsuper_21sl_arigod.jpg',
                'detail' => 'Thuốc trừ bệnh hiệu quả cho cây trồng, đặc biệt đối với bệnh đạo ôn.',
            ],
            [
                'name' => 'ĐẶC TRỊ VI KHUẨN BACLA 50SC',
                'status' => 'active',
                'image' => 'dac_tri_vi_khuan_bacla_50sc.jpg',
                'detail' => 'Thuốc đặc trị vi khuẩn, giúp ngăn ngừa và trị bệnh vi khuẩn trên cây lúa.',
            ],
            [
                'name' => 'STARSUPER 21SL',
                'status' => 'active',
                'image' => 'starsuper_21sl.jpg',
                'detail' => 'Thuốc trừ bệnh phổ rộng, hiệu quả cao đối với nhiều loại bệnh hại.',
            ],
            [
                'name' => 'OMEGA-DOWNYRUST 48WG',
                'status' => 'active',
                'image' => 'omega_downyrust_48wg.jpg',
                'detail' => 'Thuốc đặc trị bệnh sương mai và gỉ sắt trên cây trồng.',
            ],
            [
                'name' => 'ZIPRA 80WP',
                'status' => 'active',
                'image' => 'zipra_80wp.jpg',
                'detail' => 'Thuốc trừ bệnh dạng bột, hiệu quả đối với nhiều loại nấm bệnh.',
            ],
            [
                'name' => 'LK-VILLA 450SC',
                'status' => 'active',
                'image' => 'lk_villa_450sc.jpg',
                'detail' => 'Thuốc trừ bệnh phổ rộng, đặc biệt hiệu quả với các bệnh do nấm gây ra.',
            ],
            [
                'name' => 'AHA 500SC',
                'status' => 'active',
                'image' => 'aha_500sc.jpg',
                'detail' => 'Thuốc đặc trị bệnh khô vằn và đạo ôn trên cây lúa.',
            ],
            [
                'name' => 'FORWAVIL 5SC',
                'status' => 'active',
                'image' => 'forwavil_5sc.jpg',
                'detail' => 'Thuốc trừ bệnh hiệu quả cao, phòng và trị nhiều bệnh hại phổ biến.',
            ],
            [
                'name' => 'GIẢI PHÁP ĐẠO ÔN – VI KHUẨN (BIMDOWMY 375SC + STAR SUPER 21SL)',
                'status' => 'active',
                'image' => 'giai_phap_dao_on_vi_khuan.jpg',
                'detail' => 'Bộ sản phẩm kết hợp giúp phòng và trị bệnh đạo ôn và vi khuẩn hiệu quả.',
            ],
            [
                'name' => 'BIMDOWMY 375SC',
                'status' => 'active',
                'image' => 'bimdowmy_375sc.jpg',
                'detail' => 'Thuốc đặc trị bệnh đạo ôn cổ bông và đạo ôn lá trên cây lúa.',
            ],
        ];

        foreach ($thuocTruBenh as $product) {
            Product::create(array_merge($product, ['category_id' => 4]));
        }

        // Danh mục 5: Thuốc trừ sâu rầy
        $thuocTruSauRay = [
            [
                'name' => 'RONADO 500EC',
                'status' => 'active',
                'image' => 'ronado_500ec.jpg',
                'detail' => 'Thuốc trừ sâu rầy bảo vệ cây trồng khỏi sâu bệnh, đặc biệt hiệu quả với rầy nâu.',
            ],
            [
                'name' => 'LORCY 265SC',
                'status' => 'active',
                'image' => 'lorcy_265sc.jpg',
                'detail' => 'Thuốc trừ sâu phổ rộng, hiệu quả với nhiều loại sâu hại trên cây lúa.',
            ],
            [
                'name' => 'OMEGA-SPIDERMITE 24SC',
                'status' => 'active',
                'image' => 'omega_spidermite_24sc.jpg',
                'detail' => 'Thuốc đặc trị nhện đỏ và nhiều loại nhện hại khác trên cây trồng.',
            ],
            [
                'name' => 'BINHFOS 50EC',
                'status' => 'active',
                'image' => 'binhfos_50ec.jpg',
                'detail' => 'Thuốc trừ sâu tiếp xúc ruột, hiệu quả với nhiều loại sâu hại.',
            ],
            [
                'name' => 'ALEX 20SC nhện gié',
                'status' => 'active',
                'image' => 'alex_20sc_nhen_gie.jpg',
                'detail' => 'Thuốc đặc trị nhện gié hại lúa, giúp bảo vệ bông lúa hiệu quả.',
            ],
            [
                'name' => 'VK.SUPERLAU 750WG',
                'status' => 'active',
                'image' => 'vk_superlau_750wg.jpg',
                'detail' => 'Thuốc trừ cỏ dạng hạt, diệt trừ hiệu quả nhiều loại cỏ dại.',
            ],
            [
                'name' => 'REDMINE 500SC',
                'status' => 'active',
                'image' => 'redmine_500sc.jpg',
                'detail' => 'Thuốc trừ sâu hiệu quả cao, đặc biệt với các loại sâu cuốn lá.',
            ],
            [
                'name' => 'Giải pháp Rầy cánh trắng (Bọ phấn trắng) TIFENA 300SC',
                'status' => 'active',
                'image' => 'giai_phap_ray_canh_trang_tifena_300sc.jpg',
                'detail' => 'Sản phẩm đặc trị rầy cánh trắng và bọ phấn trắng, bảo vệ cây trồng hiệu quả.',
            ],
        ];

        foreach ($thuocTruSauRay as $product) {
            Product::create(array_merge($product, ['category_id' => 5]));
        }
    }
}
