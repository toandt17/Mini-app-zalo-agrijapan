<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AgentSeeder extends Seeder
{
    // Google Maps API Key - hãy thay bằng API key của bạn
    protected $googleMapsApiKey = ''; // Để trống nếu không sử dụng

    // Biến điều khiển có nên sử dụng API Google Maps hay không
    protected $useGoogleMapsApi = false;

    // URL gốc của ứng dụng frontend
    protected $frontendUrl = 'https://thiepcuoitoandao.id.vn'; // Thay đổi URL thành domain thực tế của bạn

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Tạo thư mục lưu mã QR nếu chưa tồn tại
            if (!Storage::exists('public/qrcodes')) {
                Storage::makeDirectory('public/qrcodes');
                Log::info("Đã tạo thư mục public/qrcodes");
            }

            // Danh sách các đại lý
            $agents = [
                [
                    'name' => 'VTNN Nguyễn Phước Minh',
                    'phone' => '0987060141',
                    'address' => 'ấp Hà Bao 1, xã Đa Phước',
                    'district_name' => 'An Phú',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hoàng Viên (Hồ Thảo)',
                    'phone' => '0834584966',
                    'address' => 'Ấp Phước Hòa, xã Phước Hưng',
                    'district_name' => 'An Phú',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hoàng Huy',
                    'phone' => '0334942424',
                    'address' => 'ấp Vĩnh an, xã Vĩnh Hội Đông',
                    'district_name' => 'An Phú',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quyền Dũng',
                    'phone' => '0394229091',
                    'address' => 'ấp Vĩnh Hội, xã Vĩnh Hội Đông',
                    'district_name' => 'An Phú',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phú Châu',
                    'phone' => '0988841616',
                    'address' => 'số 194, tổ 6, khóm Thạnh An, TT. Vĩnh Thạnh Trung',
                    'district_name' => 'Châu Phú',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Minh Đạt',
                    'phone' => '0965392452',
                    'address' => 'ấp Hòa Long 2, TT. An Châu',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Sang',
                    'phone' => '0826924924',
                    'address' => 'Lộ tẻ Tri Tôn, Bình Hòa',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Huyền Đoan',
                    'phone' => '0984073277',
                    'address' => 'ấp Phú Hòa 1, xã Bình Hòa',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tuấn Thúy',
                    'phone' => '0819990816',
                    'address' => 'ấp Cần Thới, xã Cần Đăng',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ngọc Tiên',
                    'phone' => '0986280043',
                    'address' => 'ấp Hòa Thuận, xã Hòa Bình Thạnh',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hiền Loan',
                    'phone' => '0384449369',
                    'address' => 'ấp Vĩnh Thuận, xã Vĩnh Hanh',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thuận Lợi',
                    'phone' => '0934778026',
                    'address' => 'Tổ 2, ấp Hòa Lợi 4, xã Vĩnh Lợi',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tuyết Thanh Sang',
                    'phone' => '0975007911',
                    'address' => 'tổ 11, ấp Đông Phú 1, xã Vĩnh Thành',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Đoàn Tùng',
                    'phone' => '0899677967',
                    'address' => '316, tổ 16, ấp Đông Phú 1, xã Vĩnh Thành',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tư Chưng (Anh Mãi)',
                    'phone' => '0972518758',
                    'address' => 'tổ 15, ấp Long Bình, xã Kiến An',
                    'district_name' => 'Chợ Mới',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Tuấn',
                    'phone' => '0765455959',
                    'address' => 'ấp Mỹ An, xã Nhơn Mỹ',
                    'district_name' => 'Chợ Mới',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Toàn Phát',
                    'phone' => '0942872969',
                    'address' => 'đường Liên Xô, khóm An Thịnh, TT. Hội An',
                    'district_name' => 'Chợ Mới',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Út Nguyên',
                    'phone' => '0961056755',
                    'address' => 'ấp Hòa Bình 2, xã Hòa Lạc',
                    'district_name' => 'Phú Tân',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trịnh Quốc Kiệt',
                    'phone' => '0834649434',
                    'address' => 'ấp Vĩnh Thạnh 2, Lê Chánh',
                    'district_name' => 'TX. Tân Châu',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thuận Trang',
                    'phone' => '0989698608',
                    'address' => 'Tổ 21 ấp Phú An A, xã Phú Vĩnh',
                    'district_name' => 'TX. Tân Châu',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Khởi Minh',
                    'phone' => '0369207291',
                    'address' => 'số 36 Tổ 3, ấp Sơn Tân, xã Vọng Đông',
                    'district_name' => 'Thoại Sơn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Kim Tuyến',
                    'phone' => '0986555252',
                    'address' => 'Tổ 8, ấp Mỹ Thới, xã Định Mỹ',
                    'district_name' => 'Thoại Sơn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Út Nhí',
                    'phone' => '0836567870',
                    'address' => 'ấp Nam Huề, xã Bình Thành',
                    'district_name' => 'Thoại Sơn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Út Pha',
                    'phone' => '0857881681',
                    'address' => 'Ấp An Lợi, xã An Hảo',
                    'district_name' => 'Tịnh Biên',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trung Hải',
                    'phone' => '0344899332',
                    'address' => '374/8 Hà Hoàng Thổ, Mỹ Hòa',
                    'district_name' => 'TP. Long Xuyên',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Văn Nhị',
                    'phone' => '0972970776',
                    'address' => 'tổ 15, ấp Tân Bình, xã Tà Đảnh',
                    'district_name' => 'Tri Tôn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN An Toàn Miền Tây',
                    'phone' => '0985011757, 0866108671',
                    'address' => 'ấp Sóc Tức, xã Lê Trì',
                    'district_name' => 'Tri Tôn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sóc Phiếp',
                    'phone' => '0358581097',
                    'address' => 'Ấp Ninh Thuận, xã An Tức',
                    'district_name' => 'Tri Tôn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trường Thịnh',
                    'phone' => '0969574321',
                    'address' => 'Cầu T6, ấp Vĩnh Phú, xã Lạc Quới',
                    'district_name' => 'Tri Tôn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Vanh Thone',
                    'phone' => '0383528132',
                    'address' => 'Ấp Phước Long, xã Ô Lâm',
                    'district_name' => 'Tri Tôn',
                    'province_name' => 'An Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN MAI THẢO',
                    'phone' => '0933012701',
                    'address' => 'Tổ 13, ấp Đông, xã Long Phước, TP Bà Rịa, Bà Rịa - Vũng Tàu',
                    'district_name' => 'TP. Bà Rịa',
                    'province_name' => 'Bà Rịa_Vũng Tàu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Vân Đương 3 (Huấn)',
                    'phone' => '0918777807',
                    'address' => 'ấp Long Hòa, TT. Phước Long',
                    'district_name' => 'Phước Long',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Lê Hải Đăng',
                    'phone' => '0842020300',
                    'address' => 'ấp Tường 1, xã Vĩnh Phú Đông',
                    'district_name' => 'Phước Long',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quốc Vệ',
                    'phone' => '0919110719',
                    'address' => 'ấp Bình Tốt, xã Vĩnh Phú Tây',
                    'district_name' => 'Phước Long',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hồng Đoan',
                    'phone' => '0949844700',
                    'address' => 'số 77 ấp Nhà Dài B, xã Châu Hưng A',
                    'district_name' => 'Vĩnh Lợi',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phước Tài',
                    'phone' => '0944444628',
                    'address' => 'xã Châu Hưng A',
                    'district_name' => 'Vĩnh Lợi',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Huỳnh Văn Khánh',
                    'phone' => '0944042327',
                    'address' => 'Ấp Giồng Bướm A, Xã Châu Thới',
                    'district_name' => 'Vĩnh Lợi',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tạ Thanh Thảo',
                    'phone' => '0366909192',
                    'address' => 'ấp 21, xã Minh Diệu',
                    'district_name' => 'Hòa Bình',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Vân Đương 5',
                    'phone' => '0763939039',
                    'address' => 'Ấp Ninh Thạnh, xã Ninh Quới A',
                    'district_name' => 'Hồng Dân',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Châu Văn Húa',
                    'phone' => '0914521287',
                    'address' => 'Khóm 2, phường Láng Tròn',
                    'district_name' => 'Gía Rai',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sang Thủy',
                    'phone' => '0919005770',
                    'address' => 'ấp 13, xã Phong Thạnh Đông',
                    'district_name' => 'TX Giá Rai',
                    'province_name' => 'Bạc Liêu',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Út Muối',
                    'phone' => '0979247978',
                    'address' => 'Ấp Phú Thuận, xã Phú Ngãi',
                    'district_name' => 'Ba Tri',
                    'province_name' => 'Bến Tre',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Huỳnh Văn Hồi',
                    'phone' => '0369504607',
                    'address' => 'ấp An Điền, xã An Hiệp',
                    'district_name' => 'Ba Tri',
                    'province_name' => 'Bến Tre',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Võ Minh Phụng',
                    'phone' => '0908557175',
                    'address' => 'số 423, ấp An Thành, phường An Tây',
                    'district_name' => 'TX Bến Cát',
                    'province_name' => 'Bình Dương',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thuận Phong',
                    'phone' => '0909804201',
                    'address' => 'đường số 3, số nhà 1, tổ 5, thôn 7, xã Nam Chính',
                    'district_name' => 'Đức Linh',
                    'province_name' => 'Bình Thuận',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thái Phước',
                    'phone' => '0375552748',
                    'address' => 'xóm 2, thôn 4, Bắc Ruộng',
                    'district_name' => 'Tánh Linh',
                    'province_name' => 'Bình Thuận',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thọ Hảo 3',
                    'phone' => '0937251414',
                    'address' => 'Tổ 2, KP Lạc Hưng 2, TT. Lạc Tánh',
                    'district_name' => 'Tánh Linh',
                    'province_name' => 'Bình Thuận',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN THIÊN AN',
                    'phone' => '0937110515',
                    'address' => '82 Quốc lộ 1A, Hòa Minh, Tuy Phong, Bình Thuận',
                    'district_name' => 'Tuy Phong',
                    'province_name' => 'Bình Thuận',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quang Minh',
                    'phone' => '0918342236',
                    'address' => 'Ấp Trùm Thuật A , xã Khánh Hải',
                    'district_name' => 'Trần Văn Thời',
                    'province_name' => 'Cà Mau',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Chí Nguyện',
                    'phone' => '0942662462',
                    'address' => 'ấp Công Nghiệp A, xã Khánh Hưng',
                    'district_name' => 'Trần Văn Thời',
                    'province_name' => 'Cà Mau',
                    'status' => 'active',
                ],
                // Thêm 50 đại lý tiếp theo (từ số 51 đến 100)
                [
                    'name' => 'VTNN Minh Lộng II',
                    'phone' => '0946733273',
                    'address' => 'chợ Cơi 5, xã Trần Hợi',
                    'district_name' => 'Trần Văn Thời',
                    'province_name' => 'Cà Mau',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Dương Đại Đổm',
                    'phone' => '0817779388',
                    'address' => 'Chợ Cơi 5A, xã Khánh Bình Tây',
                    'district_name' => 'Trần Văn Thời',
                    'province_name' => 'Cà Mau',
                    'status' => 'active',
                ],
                [
                    'name' => 'Siêu thị nông nghiệp Thái Dương',
                    'phone' => '0939690179',
                    'address' => 'đối diện trường Hà Huy Giáp Ấp Thạnh Hưng, thị trấn Cờ Đỏ',
                    'district_name' => 'Cờ Đỏ',
                    'province_name' => 'Cần Thơ',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thành Bạo',
                    'phone' => '0931099033',
                    'address' => 'ấp Đông Hòa, xã Đông Thuận, Thới Lai, Cần Thơ',
                    'district_name' => 'Thới Lai',
                    'province_name' => 'Cần Thơ',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hiệp Hưng',
                    'phone' => '0778246252',
                    'address' => 'Ấp Phú Thọ, xã Trường Xuân',
                    'district_name' => 'Thới Lai',
                    'province_name' => 'Cần Thơ',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trí Hải',
                    'phone' => '0939314711',
                    'address' => 'Qui Lân 7, xã Thạnh Qưới',
                    'district_name' => 'Vĩnh Thạnh',
                    'province_name' => 'Cần Thơ',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Đăng Khoa',
                    'phone' => '0383933653',
                    'address' => 'tổ 2, ấp 2, xã Bình Hàng Trung',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tuân An',
                    'phone' => '0344338547',
                    'address' => 'ấp 3, xã Tân Hội Trung',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phú Thông',
                    'phone' => '0357910108',
                    'address' => 'số 12, tổ 6, ấp 2, xã Mỹ Ngãi',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phúc Tiến',
                    'phone' => '0977897655',
                    'address' => 'số 60 đường Ba Sao, tổ 6, ấp 4, Mỹ Tân',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Lúa Xanh 7',
                    'phone' => '0708277799',
                    'address' => 'ấp 2, xã Mỹ Tân',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hữu Khánh',
                    'phone' => '0916357578',
                    'address' => 'ấp Mỹ Đông, xã Mỹ Thọ',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nhựt Anh',
                    'phone' => '0898818428',
                    'address' => 'Ấp Mỹ Đông Nhì, xã Mỹ Thọ',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Kim Mơ',
                    'phone' => '0386548785',
                    'address' => 'ấp Mỹ Đông 4, xã Mỹ Thọ',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Việt Hà (Qúy)',
                    'phone' => '0335353531',
                    'address' => '476 tổ 7 ấp Mỹ Đông Nhi, xã Mỹ Thọ',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Tú',
                    'phone' => '0899007746',
                    'address' => 'ấp Bình Dân, xã Nhị Mỹ',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Văn Sum',
                    'phone' => '0342943089',
                    'address' => 'ấp Hòa Dân, xã Nhị Mỹ, Cao Lãnh, Đồng Tháp',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Lê Thị Hồng Gấm',
                    'phone' => '0921102504',
                    'address' => 'ấp 3, xã Phương Trà',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Văn Lực',
                    'phone' => '0977221717',
                    'address' => 'ấp 6, xã Tân Hội Trung',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hùng',
                    'phone' => '0939821211',
                    'address' => 'ấp 1, xã Tân Hội Trung',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Duy Khánh',
                    'phone' => '0342972917',
                    'address' => 'trên trường thcs Phương Trà 200m, Xã Phương Trà',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hoàng Phát',
                    'phone' => '0938986667',
                    'address' => 'Tổ 2 ấp 6, xã Tân Hội Trung',
                    'district_name' => 'Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tấn Tài',
                    'phone' => '0838007700',
                    'address' => 'Đường điện biên phủ, xã mỹ trà',
                    'district_name' => 'TP.Cao Lãnh',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Đỗ Văn Tâm',
                    'phone' => '0919776799',
                    'address' => 'ấp 2, xã Thường Phước 1',
                    'district_name' => 'Hồng Ngự',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Bảo Toàn',
                    'phone' => '0359962868',
                    'address' => 'ấp 1, xã Thường Phước 2',
                    'district_name' => 'Hồng Ngự',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tường Nhung',
                    'phone' => '0835150144',
                    'address' => 'Khóm Thượng 2, TT. Thường Thới Tiền',
                    'district_name' => 'Hồng Ngự',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Út Thông',
                    'phone' => '0942424724',
                    'address' => 'khóm 2, phường An Bình B',
                    'district_name' => 'TX. Hồng Ngự',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trung Dũng',
                    'phone' => '0336557949',
                    'address' => '269 ấp Tân An, xã Bình Thạnh Trung',
                    'district_name' => 'Lấp Vò',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'ĐẠI LÝ NĂM LONG',
                    'phone' => '0782828292',
                    'address' => 'Âp Hòa Bình, Âp Hòa Bình, Xã Long Lắng',
                    'district_name' => 'Lai Vung',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Minh Dũ',
                    'phone' => '0985766778',
                    'address' => '114/3A, Phú Thuận, Tân Phú Đông',
                    'district_name' => 'Sa Đéc',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hai Tỷ',
                    'phone' => '0369074342',
                    'address' => 'ấp An Phú, xã An Long',
                    'district_name' => 'Tam Nông',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Minh Phúc',
                    'phone' => '0907679299',
                    'address' => 'ấp B, xã Phú Cường',
                    'district_name' => 'Tam Nông',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Công Doanh',
                    'phone' => '0782524208',
                    'address' => 'TT. Tràm Chim',
                    'district_name' => 'Tam Nông',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thiện Phát',
                    'phone' => '0989996976',
                    'address' => 'ấp A, xã Phú Cường',
                    'district_name' => 'Tam Nông',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Lộc Thọ',
                    'phone' => '0972640830',
                    'address' => 'địa chỉ ấp Công Tạo, xã Bình Phú',
                    'district_name' => 'Tân Hồng',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN AgriHope cửa hàng số 8',
                    'phone' => '0962718588',
                    'address' => 'ấp Công Tạo, xã Bình Phú',
                    'district_name' => 'Tân Hồng',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hoàng Bách',
                    'phone' => '0939909710',
                    'address' => 'ấp Đuôi Tôm, xã Tân Hộ Cơ',
                    'district_name' => 'Tân Hồng',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Châu Mỹ',
                    'phone' => '0383005952',
                    'address' => 'ấp 2, xã Phú Lợi',
                    'district_name' => 'Thanh Bình',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Mai Bảo Long',
                    'phone' => '0909183535',
                    'address' => 'ấp 2, xã Phú Lợi',
                    'district_name' => 'Thanh Bình',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tiền Đăng',
                    'phone' => '0907313994',
                    'address' => 'số nhà 313, khóm Tân Thuận, TT Thanh Bình',
                    'district_name' => 'Thanh Bình',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Văn Có',
                    'phone' => '0986746335',
                    'address' => 'Ấp 2B, xã Hưng Thạnh',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Giang Ngọc',
                    'phone' => '0907147190',
                    'address' => 'ấp 1, xã Hưng Thạnh',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quốc Khánh',
                    'phone' => '0929391368',
                    'address' => 'Ấp 1, xã Mỹ Đông',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nguyễn Văn Út',
                    'phone' => '0345674692',
                    'address' => 'Ấp 3, xã Mỹ Hoà',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hiệp Ý',
                    'phone' => '0906901612',
                    'address' => 'ấp 1, KDC Gò Tháp, xã Tân Kiều',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sáu Tửu',
                    'phone' => '0939282423',
                    'address' => 'ấp Mỹ Quới B, xã Hòa An',
                    'district_name' => 'Phụng Hiệp',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sáu Dô',
                    'phone' => '0939209390',
                    'address' => 'Ấp 8, xã Hòa An',
                    'district_name' => 'Phụng Hiệp',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phúc Thịnh',
                    'phone' => '0902703117',
                    'address' => 'ấp Phương Qưới C, xã Phương Bình',
                    'district_name' => 'Phụng Hiệp',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tám Nhỏ',
                    'phone' => '0939182024',
                    'address' => 'Ấp Tân Long, xã Tân Bình',
                    'district_name' => 'Phụng Hiệp',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ba Tròn',
                    'phone' => '0977706070',
                    'address' => 'Ấp Long Hưng 2, xã Tân Phú',
                    'district_name' => 'TX. Long Mỹ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Năm Lén',
                    'phone' => '0335269709',
                    'address' => 'ấp 6, xã Thuận Hưng',
                    'district_name' => 'Long Mỹ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Gia Bảo',
                    'phone' => '00906959592',
                    'address' => 'KV Long Khánh, phường Trà Lồng',
                    'district_name' => 'TX. Long Mỹ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tư Thích',
                    'phone' => '0868811005',
                    'address' => 'Khu vực 3, phường Trà Lồng',
                    'district_name' => 'TX. Long Mỹ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thắng',
                    'phone' => '0976509199',
                    'address' => 'ấp Châu Thành, xã An Ninh',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nhã Hân',
                    'phone' => '0866050424',
                    'address' => 'ấp Cống Đôi, xã Hồ Đắc Kiện',
                    'district_name' => 'Châu Thanh',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Vũ Quyết',
                    'phone' => '0945555212',
                    'address' => 'Cống Đôi, Hồ Đắc Kiện',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sáu Phân (Diễm)',
                    'phone' => '0939600630',
                    'address' => 'Ấp Mỹ Phú, xã Thiện Mỹ',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nguyễn Phước Toàn',
                    'phone' => '0964390696',
                    'address' => 'Ấp 9, xã Trinh Phú',
                    'district_name' => 'Kế Sách',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Kim Phụng',
                    'phone' => '0976069979',
                    'address' => 'ấp An Ninh, TT Kế Sách',
                    'district_name' => 'Kế Sách',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Gia Nguyễn',
                    'phone' => '0919795451',
                    'address' => 'Ấp số 1, xã Đại Hải',
                    'district_name' => 'Kế Sách',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Tân',
                    'phone' => '0984599722',
                    'address' => 'ấp 5, TT Long Phú',
                    'district_name' => 'Long Phú',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ngọc Tỷ',
                    'phone' => '0916777000',
                    'address' => '490 Ấp Mỹ Thuận, TT. Huỳnh Hữu Nghĩa',
                    'district_name' => 'Mỹ Tú',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sáu Hào',
                    'phone' => '0966674680',
                    'address' => 'ấp Thới B, xã Mỹ Phước',
                    'district_name' => 'Mỹ Tú',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phong Mỵ',
                    'phone' => '0365907405',
                    'address' => 'ấp Phương An B, xã Mỹ Phước',
                    'district_name' => 'Mỹ Tú',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ngọc Giàu',
                    'phone' => '0387694140',
                    'address' => 'ấp Phú Tức, xã Phú Mỹ',
                    'district_name' => 'Mỹ Tú',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Mỹ Hương',
                    'phone' => '0969422495',
                    'address' => 'ấp Đào Viên, xã Thạnh Quới',
                    'district_name' => 'Mỹ Xuyên',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Huỳnh Dũng',
                    'phone' => '035352052',
                    'address' => 'ấp Tân Bình, xã Long Bình',
                    'district_name' => 'TX. Ngã Năm',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Mười Biết',
                    'phone' => '0933131517',
                    'address' => 'Ấp Long Thành, xã Tân Long',
                    'district_name' => 'TX. Ngã Năm',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Đại Phúc',
                    'phone' => '0944025404',
                    'address' => 'ấp mỹ phước , xã mỹ bình',
                    'district_name' => 'TX. Ngã Năm',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Minh Trí',
                    'phone' => '0978868629',
                    'address' => 'ấp Mỹ Tây B, xã Mỹ Quới',
                    'district_name' => 'TX. Ngã Năm',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sơn Hải (Sơn Lẻn)',
                    'phone' => '0985202242',
                    'address' => 'Lâm Tân',
                    'district_name' => 'Thạnh Trị',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tám Miễn',
                    'phone' => '0909959762',
                    'address' => 'Ấp Tân Lộc, xã Lâm Tân',
                    'district_name' => 'Thạnh Trị',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hai Six',
                    'phone' => '0986997771',
                    'address' => 'ấp 21, xã Thạnh Tân',
                    'district_name' => 'Thạnh Trị',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hưng Phát',
                    'phone' => '0914685997',
                    'address' => 'ấp 8, TT. Hưng Lợi',
                    'district_name' => 'Thạnh Trị',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Mùa Vàng 4',
                    'phone' => '0988585665',
                    'address' => 'Ấp 15, xã Vĩnh Lợi',
                    'district_name' => 'Thạnh Trị',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thu',
                    'phone' => '0945554678',
                    'address' => 'ấp Tân Nghĩa, xã Lâm Tân',
                    'district_name' => 'Thạnh Trị',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ngọc Ánh',
                    'phone' => '0907464624',
                    'address' => '173B Cao Thắng, K7, P8',
                    'district_name' => 'TP. Sóc Trăng',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'Đại Lý Khương Thạnh Phát (Đại Lý Chị Khương)',
                    'phone' => '0982825498',
                    'address' => 'Số 400 Lê Duẩn, P4',
                    'district_name' => 'TP. Sóc Trăng',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'ĐẠI LÝ NGỌC PHẤN',
                    'phone' => '0916944701',
                    'address' => '226 Lý Thường Kiệt, phường 4',
                    'district_name' => 'TP. Sóc Trăng',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tư Lư (anh Hưởng Bán)',
                    'phone' => '0339603660',
                    'address' => 'Ấp Tiên Cường 1, xã Thạnh Thới An',
                    'district_name' => 'Trần Đề',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trường Kỳ',
                    'phone' => '0918102777',
                    'address' => 'xã Thạnh Thới Thuận',
                    'district_name' => 'Trần Đề',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trường Phát',
                    'phone' => '0908666650',
                    'address' => 'ấp Giồng Giũa, TT. Lịch Hội Thượng',
                    'district_name' => 'Trần Đề',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Khởi',
                    'phone' => '0358396285',
                    'address' => 'ấp Tiếp Nhựt,  xã Viên An',
                    'district_name' => 'Trần Đề',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Lợi Thắm',
                    'phone' => '0987420320',
                    'address' => 'ấp Đào Viên, xã Viên Bình',
                    'district_name' => 'Trần Đề',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Như Ý',
                    'phone' => '0377285476',
                    'address' => 'khóm Vĩnh Tiền, phường 3',
                    'district_name' => 'TX. Ngã Năm',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN NHƯ LỘC',
                    'phone' => '0366812501',
                    'address' => 'ấp Nam Chánh, xã Lịch Hội Thượng, Trần Đề, Sóc Trăng',
                    'district_name' => 'Trần Đề',
                    'province_name' => 'Sóc Trăng',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Vạn Thành 2',
                    'phone' => '0909385252',
                    'address' => 'Ấp Phươớc Bình, xã Phước Thạnh',
                    'district_name' => 'Gò Dầu',
                    'province_name' => 'Tây Ninh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Diễm Phúc',
                    'phone' => '0335226171',
                    'address' => 'cầu xe Hưng Thuận',
                    'district_name' => 'TX. Trảng Bàng',
                    'province_name' => 'Tây Ninh',
                    'status' => 'active',
                    'description' => 'Đại lý vật tư nông nghiệp (Redmine)',
                ],
                [
                    'name' => 'Đại Lý Ngọc Trâm',
                    'phone' => '0938106000',
                    'address' => 'phân bón ngọc trâm,  xã tân hiệp',
                    'district_name' => 'Tân Châu',
                    'province_name' => 'Tây Ninh',
                    'status' => 'active',
                    'description' => 'Đại lý vật tư nông nghiệp (kali)',
                ],
                [
                    'name' => 'VTNN Thành Tính',
                    'phone' => '0962488232',
                    'address' => 'ấp Hậu Quới, xã Hậu Mỹ Bắc B',
                    'district_name' => 'Cái Bè',
                    'province_name' => 'Tiền Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ngọc Hân',
                    'phone' => '0939649095',
                    'address' => 'ấp 5A, Xã Phú Cường',
                    'district_name' => 'Cai Lậy',
                    'province_name' => 'Tiền Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tiến Lợi',
                    'phone' => '0983660420',
                    'address' => 'ĐT 868 ấp Mỹ Hòa, x. Mỹ Thạnh Trung',
                    'district_name' => 'TX. Cai Lậy',
                    'province_name' => 'Tiền Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tấn Lợi',
                    'phone' => '0976540179',
                    'address' => 'ấp Bình Cách, xã Yên Luông',
                    'district_name' => 'Gò Công Tây',
                    'province_name' => 'Tiền Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Chiến Hạnh',
                    'phone' => '0962644744',
                    'address' => 'ấp Cây Gòn, xã Phong Thạnh',
                    'district_name' => 'Cầu Kè',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Pu Hùng',
                    'phone' => '0339793417',
                    'address' => 'ấp Ô Tưng B, xã Châu Điền',
                    'district_name' => 'Cầu Kè',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'Cửa Hàng Hoa Vinh',
                    'phone' => '0386267211',
                    'address' => 'ấp Phú Lân, xã Song Lộc',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quốc Ngân',
                    'phone' => '0917253732',
                    'address' => 'ấp Láng Khoét, xã Song Lộc',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Khánh Vy',
                    'phone' => '0356344048',
                    'address' => 'ấp Tà Rom B, xã Đôn Châu',
                    'district_name' => 'Duyên Hải',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hảo Linh',
                    'phone' => '0977698398',
                    'address' => 'ấp Ngãi Phú, xã Ngãi Hùng',
                    'district_name' => 'Tiểu Cần',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nông Phát',
                    'phone' => '0898087750',
                    'address' => 'ấp Nhứt, xã Tân Hùng',
                    'district_name' => 'Tiểu Cần',
                    'province_name' => 'Trà Vinh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tuấn Thịnh',
                    'phone' => '0971000497',
                    'address' => 'ấp Nước Xoáy, xã Tân An Luông',
                    'district_name' => 'Vũng Liêm',
                    'province_name' => 'Vĩnh Long',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Việt Bằng',
                    'phone' => '0907211226',
                    'address' => 'ấp Thạnh An, xã Đông Thạnh',
                    'district_name' => 'TX. Bình Minh',
                    'province_name' => 'Vĩnh Long',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phi Lộc',
                    'phone' => '0939344353',
                    'address' => 'ấp 5A, xã Trường Xuân',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thắng Nguyên',
                    'phone' => '0379464748',
                    'address' => 'địa chỉ 87D Trần Phú, TT. Mỹ An',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Vũ Khương',
                    'phone' => '0939841352',
                    'address' => 'ấp Mỹ Phước 2, xã Mỹ Quý, Tháp Mười, Đồng Tháp',
                    'district_name' => 'Tháp Mười',
                    'province_name' => 'Đồng Tháp',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thật Ly',
                    'phone' => '0968241024',
                    'address' => 'Kinh thầy 5, ấp 7, xã Long Trị A',
                    'district_name' => 'TX. Long Mỹ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Toại',
                    'phone' => '0906151747',
                    'address' => 'ấp 7, xã Lương Nghĩa',
                    'district_name' => 'Long Mỹ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Đại Thành',
                    'phone' => '0979991931',
                    'address' => 'Ấp 3, xã Vị Đông',
                    'district_name' => 'Vị Thuỷ',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ba Siêu 1000',
                    'phone' => '0879208500',
                    'address' => 'Ấp Thị Tứ, TT. Một Ngàn',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Bé Tư',
                    'phone' => '0949898915',
                    'address' => '10/203C ấp 2, x. Tân Nhựt h. Bình Chánh Tp.HCM',
                    'district_name' => 'Bình Chánh',
                    'province_name' => 'Hồ Chí Minh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nguyễn Đức Huy',
                    'phone' => '0963752771',
                    'address' => 'C6/162, ấp 3, Tân Nhựt',
                    'district_name' => 'Bình Chánh',
                    'province_name' => 'Hồ Chí Minh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Trần Gia An',
                    'phone' => '0933751432',
                    'address' => '185 tỉnh lộ 8, ấp Mũi Lớn 1, xã Tân An Hội',
                    'district_name' => 'Củ Chi',
                    'province_name' => 'Hồ Chí Minh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hai Tuồng',
                    'phone' => '0965006373',
                    'address' => '741 Nguyễn Thị Rành, Nhuận Đức',
                    'district_name' => 'Củ Chi',
                    'province_name' => 'Hồ Chí Minh',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nguyễn Trường Phát',
                    'phone' => '0944990213',
                    'address' => 'ấp Sua Đũa, xã Vĩnh Hoà Hiệp, Châu Thành, Kiên Giang',
                    'district_name' => 'Châu Thành',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Đại Thành 2',
                    'phone' => '0979991931',
                    'address' => 'ấp Hòa Phú, xã Hòa Hưng',
                    'district_name' => 'Giồng Riềng',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tuyết Minh',
                    'phone' => '0916357395',
                    'address' => 'số 76, Hòa Tân, xã Hòa Hưng',
                    'district_name' => 'Giồng Riềng',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phương Thuy',
                    'phone' => '0706664080',
                    'address' => 'ấp Thạnh Đông, xã Thạnh Phước',
                    'district_name' => 'Giồng Riềng',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tư Phú',
                    'phone' => '0366000022',
                    'address' => 'Ấp Vĩnh Lợi, xã Vĩnh Điều',
                    'district_name' => 'Giang Thành',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tuấn T4',
                    'phone' => '0977822585',
                    'address' => '122 ấp t4 xã Vĩnh Phú',
                    'district_name' => 'Giang Thành',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Ngô Quốc Minh',
                    'phone' => '0932670791',
                    'address' => 'ấp châu thành, xã thủy liễu',
                    'district_name' => 'Gò Quao',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quanh Na',
                    'phone' => '0369767503',
                    'address' => 'ấp Vạn Thanh, xã Thổ Sơn',
                    'district_name' => 'Hòn Đất',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nhi Dũng',
                    'phone' => '0973282842',
                    'address' => 'tổ 6, ấp Tàu Hơi B, xã Thạnh Trị',
                    'district_name' => 'Tân Hiệp',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nguyễn Thanh Hoàng',
                    'phone' => '0377810868',
                    'address' => 'ấp Tân Hà B, xã Tân Hòa',
                    'district_name' => 'Tân Hiệp',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tình Bảy',
                    'phone' => '0385533767',
                    'address' => 'ấp Hòa Bình, xã Vĩnh Hòa',
                    'district_name' => 'U Minh Thượng',
                    'province_name' => 'Kiên Giang',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hưng Phát',
                    'phone' => '0359137427',
                    'address' => 'Ấp Voi, xã Mỹ Thạnh Tây',
                    'district_name' => 'Đức Huệ',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hữu Văn',
                    'phone' => '0388658622',
                    'address' => 'Ấp 5, xã mỹ thạnh bắc',
                    'district_name' => 'Đức Huệ',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Nông Gia Phát',
                    'phone' => '0919156494',
                    'address' => 'Ấp 3, xã Bình Hòa Đông',
                    'district_name' => 'Mộc Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hải Đông',
                    'phone' => '0375989869',
                    'address' => 'số 370, ấp Gò Vồ Nhỏ, xã Bình Thạnh',
                    'district_name' => 'Mộc Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN R3 (Lâm Tấn Đạt)',
                    'phone' => '0908002440',
                    'address' => 'Ấp Gò Vồ Nhỏ, xã Bình Thạnh',
                    'district_name' => 'Mộc Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hai Cường',
                    'phone' => '0834447345',
                    'address' => 'ấp Mương Khai, xã Tân Thành',
                    'district_name' => 'Mộc Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Quang Vinh',
                    'phone' => '0704948006',
                    'address' => 'Ấp Gò Gòn, xã Hưng Thạnh',
                    'district_name' => 'Tân Hưng',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tấn Tài Vcb',
                    'phone' => '0987914778',
                    'address' => 'TL 831, ấp 5, xã Vĩnh Châu B',
                    'district_name' => 'Tân Hưng',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hoàng Minh Thư',
                    'phone' => '0934378338',
                    'address' => 'Ấp Vàm Gừa, xã Vĩnh Bửu',
                    'district_name' => 'Tân Hưng',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Tiền',
                    'phone' => '0369323423',
                    'address' => 'Khu Phố Rọc Chanh, TT. Tân Hưng',
                    'district_name' => 'Tân Hưng',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Bảy Sơn',
                    'phone' => '0964064344',
                    'address' => 'ấp Phước Cường, xã Hậu Thạnh Tây',
                    'district_name' => 'Tân Thạnh',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tấn Bình',
                    'phone' => '0942098749',
                    'address' => '88 ấp 1, xã Lạc Tấn',
                    'district_name' => 'Tân Trụ',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Thanh Hùng',
                    'phone' => '0848240518',
                    'address' => 'Kế UBND xã Đức Tân, Bình Lợi, Đức Tân, Tân Trụ, Long An',
                    'district_name' => 'Tân Trụ',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Kim Dung',
                    'phone' => '0855581355',
                    'address' => 'ấp 2, xã Tân Đông',
                    'district_name' => 'Thạnh Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Phan Minh Thịnh',
                    'phone' => '0919823652',
                    'address' => 'ấp Nước Trong, xã Thủy Đông',
                    'district_name' => 'Thạnh Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hưng Phát',
                    'phone' => '0367953039',
                    'address' => 'ấp 3, xã Thạnh An',
                    'district_name' => 'Thạnh Hóa',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tư Quốc',
                    'phone' => '0353499764',
                    'address' => '69 ấp Cái Đôi Đông, xã Bình Tân',
                    'district_name' => 'TX Kiến Tường',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hùng Giang',
                    'phone' => '0976596061',
                    'address' => 'ấp Sồ Đô, xã Thạnh Hưng',
                    'district_name' => 'TX Kiến Tường',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Võ Công Danh',
                    'phone' => '0829409904',
                    'address' => 'ấp Bình Tây, xã Tuyên Thạnh, TX Kiến Tường, LA',
                    'district_name' => 'TX. Kiến Tường',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Tư Tấn',
                    'phone' => '0378293656',
                    'address' => 'ấp 3, xã Thạnh Trị',
                    'district_name' => 'TX Kiến Tường',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Sáu Ngân',
                    'phone' => '0838042627',
                    'address' => '210 bắc chan 1 , xã tuyên thạnh',
                    'district_name' => 'TX. Kiến Tường',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Hân Hà',
                    'phone' => '0377470759',
                    'address' => 'ấp Sậy Giăng, xã Khánh Hưng',
                    'district_name' => 'Vĩnh Hưng',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                [
                    'name' => 'VTNN Kim Quý (Lâm)',
                    'phone' => '0979814154',
                    'address' => '194 ấp Rạch Đình, xã Tuyên Bình',
                    'district_name' => 'Vĩnh Hưng',
                    'province_name' => 'Long An',
                    'status' => 'active',
                ],
                // Kết thúc các đại lý bổ sung
                [
                    'name' => 'VTNN Sáu Tửu',
                    'phone' => '0939282423',
                    'address' => 'ấp Mỹ Quới B, xã Hòa An',
                    'district_name' => 'Phụng Hiệp',
                    'province_name' => 'Hậu Giang',
                    'status' => 'active',
                ],
            ];

            // Tạo đại lý từ dữ liệu
            foreach ($agents as $agentData) {
                // Tìm province
                $province = Province::where('name', 'like', '%' . $agentData['province_name'] . '%')->first();
                if (!$province) {
                    Log::warning("Không tìm thấy tỉnh/thành phố: " . $agentData['province_name']);
                    continue;
                }

                // Tìm district
                $district = District::where('name', 'like', '%' . $agentData['district_name'] . '%')
                    ->where('province_id', $province->id)
                    ->first();
                if (!$district) {
                    Log::warning("Không tìm thấy quận/huyện: " . $agentData['district_name'] . " thuộc " . $province->name);
                    continue;
                }

                // Tìm ward dựa vào địa chỉ (nếu có thể trích xuất từ địa chỉ)
                $ward = null;
                $ward_id = null;

                // Phân tích địa chỉ để tìm xã/phường
                $addressParts = explode(',', $agentData['address']);
                $addressParts = array_merge($addressParts, explode(' ', $agentData['address']));

                // Tìm kiếm các từ khóa xã, phường, thị trấn
                $wardNames = [];
                foreach ($addressParts as $part) {
                    $part = trim($part);
                    if (preg_match('/(xã|phường|thị trấn|TT\.)\s+([^,]+)/i', $part, $matches)) {
                        $wardNames[] = $matches[2];
                    }
                    // Hoặc nếu chỉ có tên không có tiền tố
                    if (str_contains(strtolower($part), 'ấp') || str_contains(strtolower($part), 'thôn')) {
                        // Lấy các phần tử tiếp theo có thể là tên xã
                        $index = array_search($part, $addressParts);
                        if ($index !== false && isset($addressParts[$index + 1])) {
                            $wardNames[] = $addressParts[$index + 1];
                        }
                    }
                }

                // Tìm kiếm ward trong database
                if (!empty($wardNames)) {
                    foreach ($wardNames as $wardName) {
                        $ward = Ward::where('name', 'like', '%' . $wardName . '%')
                            ->where('district_id', $district->id)
                            ->first();

                        if ($ward) {
                            $ward_id = $ward->id;
                            break;
                        }
                    }
                }

                // Nếu không tìm thấy ward, thử lấy một ward ngẫu nhiên từ district
                if (!$ward_id) {
                    $randomWard = Ward::where('district_id', $district->id)->inRandomOrder()->first();
                    if ($randomWard) {
                        $ward_id = $randomWard->id;
                    }
                }

                // Lấy địa chỉ đầy đủ để gọi Google Maps API
                $fullAddress = $agentData['address'] . ', ' . $agentData['district_name'] . ', ' . $agentData['province_name'] . ', Việt Nam';

                // Mặc định sử dụng tọa độ ngẫu nhiên
                $latitude = 10 + (rand(0, 1000) / 1000);
                $longitude = 106 + (rand(0, 1000) / 1000);

                // Nếu có API key và cấu hình sử dụng Google Maps API
                if ($this->useGoogleMapsApi && !empty($this->googleMapsApiKey)) {
                    try {
                        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($fullAddress) . "&key=" . $this->googleMapsApiKey;
                        $geocodeResponse = Http::get($geocodeUrl);

                        if ($geocodeResponse->successful() && isset($geocodeResponse['results'][0]['geometry']['location'])) {
                            $location = $geocodeResponse['results'][0]['geometry']['location'];
                            $latitude = $location['lat'];
                            $longitude = $location['lng'];
                            Log::info("Đã lấy được tọa độ cho {$agentData['name']}: [{$latitude}, {$longitude}]");
                        } else {
                            Log::warning("Không thể lấy tọa độ từ Google Maps API cho " . $agentData['name']);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Lỗi khi gọi Google Maps API: " . $e->getMessage());
                    }
                } else {
                    // Nếu không sử dụng Google Maps, tạo tọa độ theo khu vực
                    $this->generateLocationByRegion($agentData['province_name'], $latitude, $longitude);
                }

                // Tạo đại lý
                $agent = Agent::create([
                    'name' => $agentData['name'],
                    'phone' => $agentData['phone'],
                    'address' => $agentData['address'],
                    'province_id' => $province->id,
                    'district_id' => $district->id,
                    'ward_id' => $ward_id,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'status' => $agentData['status'] ?? 'active',
                    'description' => 'Đại lý vật tư nông nghiệp',
                    'open_hours' => '7:30 - 17:30',
                ]);

                // Tạo mã QR cho đại lý
                $this->generateQrCode($agent);
            }

            Log::info("Đã nhập dữ liệu đại lý thành công");
        } catch (\Exception $e) {
            Log::error("Lỗi khi tạo dữ liệu đại lý: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    /**
     * Tạo mã QR cho đại lý
     *
     * @param Agent $agent Đại lý cần tạo mã QR
     */
    private function generateQrCode(Agent $agent)
    {
        try {
            // Tạo timestamp và mã hóa base64
            $timestamp = time();
            $encodedTimestamp = base64_encode($timestamp);

            // Tạo đường dẫn tới trang chi tiết đại lý với format mới
            $agentUrl = "{$this->frontendUrl}/agent/qr/{$agent->id}?created={$encodedTimestamp}";
            Log::info("URL đại lý: {$agentUrl}");

            // Tên file QR code - đổi từ PNG sang SVG
            $fileName = "agent_{$agent->id}.svg";
            $qrPath = "public/qrcodes/{$fileName}";
            Log::info("Path lưu QR: " . storage_path("app/{$qrPath}"));

            // Kiểm tra thư mục tồn tại
            if (!Storage::exists('public/qrcodes')) {
                Storage::makeDirectory('public/qrcodes');
                Log::info("Đã tạo thư mục public/qrcodes");
            }

            // Đảm bảo có quyền ghi (nếu chạy trên Linux/Unix)
            if (function_exists('chmod')) {
                @chmod(storage_path('app/public/qrcodes'), 0755);
            }

            // Lưu QR trực tiếp vào file - đổi format('png') thành format('svg')
            try {
                QrCode::format('svg')
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate($agentUrl, storage_path("app/{$qrPath}"));

                Log::info("Đã tạo file QR: " . storage_path("app/{$qrPath}"));
            } catch (\Exception $e) {
                Log::error("Lỗi khi tạo QR: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                return;
            }

            // Kiểm tra file đã tạo
            if (file_exists(storage_path("app/{$qrPath}"))) {
                Log::info("File QR đã tồn tại");
            } else {
                Log::error("File QR không tồn tại sau khi tạo!");
                return;
            }

            // Cập nhật đường dẫn QR code trong đại lý
            try {
                // Sử dụng phương pháp tương đối chắc chắn hơn
                $agent->qr_code = '/storage/qrcodes/' . $fileName;
                $agent->save();

                // Kiểm tra sau khi lưu
                $updatedAgent = Agent::find($agent->id);
                Log::info("QR code sau khi lưu: " . $updatedAgent->qr_code);
            } catch (\Exception $e) {
                Log::error("Lỗi khi cập nhật QR path: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            }
        } catch (\Exception $e) {
            Log::error("Lỗi tổng thể: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }

    /**
     * Tạo tọa độ theo vùng miền
     *
     * @param string $provinceName Tên tỉnh/thành phố
     * @param float &$latitude Biến tham chiếu vĩ độ
     * @param float &$longitude Biến tham chiếu kinh độ
     */
    private function generateLocationByRegion($provinceName, &$latitude, &$longitude)
    {
        // Các tọa độ trung tâm khu vực
        $regions = [
            'An Giang' => [10.5, 105.2],
            'Bà Rịa_Vũng Tàu' => [10.5, 107.2],
            'Bạc Liêu' => [9.3, 105.7],
            'Bắc Giang' => [21.3, 106.2],
            'Bắc Kạn' => [22.1, 105.8],
            'Bến Tre' => [10.2, 106.4],
            'Bình Dương' => [11.0, 106.6],
            'Bình Định' => [13.8, 109.0],
            'Bình Phước' => [11.7, 106.9],
            'Bình Thuận' => [11.1, 108.0],
            'Cà Mau' => [9.2, 105.1],
            'Cần Thơ' => [10.0, 105.8],
            'Đà Nẵng' => [16.0, 108.2],
            'Đắk Lắk' => [12.7, 108.3],
            'Đồng Nai' => [10.9, 107.0],
            'Đồng Tháp' => [10.5, 105.7],
            'Hà Nội' => [21.0, 105.8],
            'Hải Phòng' => [20.8, 106.7],
            'Hậu Giang' => [9.8, 105.5],
            'Hồ Chí Minh' => [10.8, 106.6],
            'Kiên Giang' => [10.0, 105.2],
            'Long An' => [10.6, 106.2],
            'Sóc Trăng' => [9.6, 105.9],
            'Tây Ninh' => [11.4, 106.1],
            'Tiền Giang' => [10.4, 106.3],
            'Trà Vinh' => [9.9, 106.3],
            'Vĩnh Long' => [10.2, 106.0],
            // Thêm các tỉnh thành khác nếu cần
        ];

        if (isset($regions[$provinceName])) {
            // Lấy tọa độ trung tâm khu vực
            $centerLat = $regions[$provinceName][0];
            $centerLng = $regions[$provinceName][1];

            // Tạo ngẫu nhiên trong phạm vi 20km từ trung tâm
            $latitude = $centerLat + (rand(-100, 100) / 1000);
            $longitude = $centerLng + (rand(-100, 100) / 1000);
        }
        // Nếu không tìm thấy trong danh sách, giữ nguyên tọa độ mặc định
    }
}
