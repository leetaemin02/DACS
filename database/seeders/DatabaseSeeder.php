<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Sach;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create an Admin User
        User::create([
            'ho_ten' => 'Admin User',
            'email' => 'admin@example.com',
            'mat_khau' => bcrypt('password'), // password is 'password'
            'vai_tro' => 'admin',
        ]);

        // 2. Create a Regular Customer
        User::create([
            'ho_ten' => 'John Doe',
            'email' => 'john@example.com',
            'mat_khau' => bcrypt('password'),
            'vai_tro' => 'khach_hang',
            'dia_chi' => '123 Main St, New York, NY',
            'so_dien_thoai' => '1234567890',
        ]);

        // 3. Create Dummy Books
        $books = [
            [
                'ten_sach' => 'Nhà Giả Kim',
                'tac_gia' => 'Paulo Coelho',
                'mo_ta' => 'Một cuốn sách về việc theo đuổi ước mơ và định mệnh của mỗi người.',
                'gia' => 15.00,
                'so_luong' => 100,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/vi/8/8f/Nh%C3%A0_gi%E1%BA%A3_kim.jpg',
            ],
            [
                'ten_sach' => 'Đắc Nhân Tâm',
                'tac_gia' => 'Dale Carnegie',
                'mo_ta' => 'Cuốn sách nổi tiếng nhất thế giới về nghệ thuật giao tiếp và chinh phục lòng người.',
                'gia' => 12.50,
                'so_luong' => 80,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/vi/3/30/%C4%90%E1%BA%AFc_nh%C3%A2n_t%C3%A2m.jpg',
            ],
            [
                'ten_sach' => 'Hoàng Tử Bé',
                'tac_gia' => 'Antoine de Saint-Exupéry',
                'mo_ta' => 'Câu chuyện ngụ ngôn sâu sắc về cuộc sống, tình yêu và bản chất con người.',
                'gia' => 10.00,
                'so_luong' => 150,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/vi/0/05/Le_Petit_Prince.jpg',
            ],
            [
                'ten_sach' => 'Bố Già',
                'tac_gia' => 'Mario Puzo',
                'mo_ta' => 'Tác phẩm kinh điển về thế giới ngầm và những quy tắc danh dự của gia đình Mafia.',
                'gia' => 20.00,
                'so_luong' => 50,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/a/af/The_Godfather_novel.jpg',
            ],
            [
                'ten_sach' => 'Chúa Tể Của Những Chiếc Nhẫn',
                'tac_gia' => 'J.R.R. Tolkien',
                'mo_ta' => 'Hành trình tiêu diệt chiếc nhẫn quyền lực trong thế giới Trung Địa hùng vĩ.',
                'gia' => 35.00,
                'so_luong' => 40,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/e/e9/First_Single_Volume_Edition_of_The_Lord_of_the_Rings.gif',
            ],
            [
                'ten_sach' => 'Harry Potter và Hòn Đá Phù Thủy',
                'tac_gia' => 'J.K. Rowling',
                'mo_ta' => 'Khởi đầu chuyến phiêu lưu kỳ ảo của cậu bé phù thủy tại trường Hogwarts.',
                'gia' => 18.00,
                'so_luong' => 200,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/6/6b/Harry_Potter_and_the_Philosopher%27s_Stone_Book_Cover.jpg',
            ],
            [
                'ten_sach' => 'Để Giết Con Chim Nhại',
                'tac_gia' => 'Harper Lee',
                'mo_ta' => 'Bài học về lòng dũng cảm, sự bao dung và công lý qua góc nhìn trẻ thơ.',
                'gia' => 14.50,
                'so_luong' => 60,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/7/79/To_Kill_a_Mockingbird.jpg',
            ],
            [
                'ten_sach' => '1984',
                'tac_gia' => 'George Orwell',
                'mo_ta' => 'Một tiểu thuyết tiên tri về một thế giới bị kiểm soát tuyệt đối.',
                'gia' => 13.00,
                'so_luong' => 90,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/c/c3/1984first.jpg',
            ],
            [
                'ten_sach' => 'Kiêu Hãnh và Định Kiến',
                'tac_gia' => 'Jane Austen',
                'mo_ta' => 'Câu chuyện tình yêu và những rào cản giai cấp trong xã hội Anh thế kỷ 19.',
                'gia' => 11.00,
                'so_luong' => 70,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/commons/1/17/PrideAndPrejudiceTitlePage.jpg',
            ],
            [
                'ten_sach' => 'Đôn Kihôtê',
                'tac_gia' => 'Miguel de Cervantes',
                'mo_ta' => 'Cuộc phiêu lưu nực cười nhưng đầy nhân văn của hiệp sĩ xứ Mancha.',
                'gia' => 22.00,
                'so_luong' => 30,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/commons/0/08/Don_Quijote_1.jpg',
            ],
            [
                'ten_sach' => 'Gatsby Vĩ Đại',
                'tac_gia' => 'F. Scott Fitzgerald',
                'mo_ta' => 'Bức tranh về sự hào nhoáng và nỗi buồn của giấc mơ Mỹ những năm 1920.',
                'gia' => 15.99,
                'so_luong' => 100,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/commons/7/7a/The_Great_Gatsby_cover_1925_copy.jpg',
            ],
            [
                'ten_sach' => 'Trăm Năm Cô Đơn',
                'tac_gia' => 'Gabriel García Márquez',
                'mo_ta' => 'Kiệt tác của chủ nghĩa hiện thực huyền ảo về dòng họ Buendía.',
                'gia' => 19.50,
                'so_luong' => 45,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/a/a0/Cien_a%C3%B1os_de_soledad_%28book_cover%29.jpg',
            ],
            [
                'ten_sach' => 'Ông Già Và Biển Cả',
                'tac_gia' => 'Ernest Hemingway',
                'mo_ta' => 'Sức mạnh của ý chí con người trước thiên nhiên dữ dội.',
                'gia' => 9.00,
                'so_luong' => 120,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/7/73/Oldmansea.jpg',
            ],
            [
                'ten_sach' => 'Sapiens: Lược Sử Loài Người',
                'tac_gia' => 'Yuval Noah Harari',
                'mo_ta' => 'Cái nhìn toàn cảnh về lịch sử tiến hóa và phát triển của con người.',
                'gia' => 25.00,
                'so_luong' => 200,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/0/06/Sapiens_A_Brief_History_of_Humankind.jpg',
            ],
            [
                'ten_sach' => 'Mật Mã Da Vinci',
                'tac_gia' => 'Dan Brown',
                'mo_ta' => 'Cuộc rượt đuổi nghẹt thở đi tìm sự thật ẩn giấu trong các tác phẩm nghệ thuật.',
                'gia' => 17.00,
                'so_luong' => 110,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/6/6b/DaVinciCode.jpg',
            ],
            [
                'ten_sach' => 'Anh Chàng Hobbit',
                'tac_gia' => 'J.R.R. Tolkien',
                'mo_ta' => 'Chuyến hành trình đầy bất ngờ của Bilbo Baggins đến Ngọn núi Cô đơn.',
                'gia' => 16.50,
                'so_luong' => 85,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/4/4a/The_Hobbit_Front_Cover.png',
            ],
            [
                'ten_sach' => 'Những Người Khốn Khổ',
                'tac_gia' => 'Victor Hugo',
                'mo_ta' => 'Bản anh hùng ca về tình yêu, sự hy sinh và công lý trong xã hội Pháp.',
                'gia' => 28.00,
                'so_luong' => 35,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/commons/1/1a/Les_Miserables_1862_First_Edition.jpg',
            ],
            [
                'ten_sach' => 'Thép Đã Tôi Thế Đấy',
                'tac_gia' => 'Nikolai Ostrovsky',
                'mo_ta' => 'Biểu tượng về nghị lực và lý tưởng sống của tuổi trẻ.',
                'gia' => 14.00,
                'so_luong' => 65,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/vi/1/1a/Thepdatoitheday.jpg',
            ],
            [
                'ten_sach' => 'Cây Cam Ngọt Của Tôi',
                'tac_gia' => 'José Mauro de Vasconcelos',
                'mo_ta' => 'Câu chuyện cảm động về tình bạn và nỗi đau trưởng thành của cậu bé Zezé.',
                'gia' => 12.00,
                'so_luong' => 130,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/en/c/cd/My_Sweet_Orange_Tree.JPG',
            ],
            [
                'ten_sach' => 'Hai Vạn Dặm Dưới Biển',
                'tac_gia' => 'Jules Verne',
                'mo_ta' => 'Khám phá thế giới đại dương kỳ thú cùng thuyền trưởng Nemo trên tàu Nautilus.',
                'gia' => 15.50,
                'so_luong' => 95,
                'hinh_anh' => 'https://upload.wikimedia.org/wikipedia/commons/1/10/20000_Leagues_Under_the_Seas_F_Ferat_01.jpg',
            ],
        ];

        foreach ($books as $book) {
            Sach::create($book);
        }
    }
}
