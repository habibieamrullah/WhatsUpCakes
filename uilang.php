<?php
    
    //Bahasa Indonesia
    function translateId(){
		global $language;
        global $rawword;
		//word definitions...
		$definitions = array(
			"About" => "Tentang",
			"Back" => "Kembali",
			"Base URL (make sure to include '/' symbol at the end)" => "URL Dasar (pastikan Anda tambahkan simbol '/' di akhir)",
			"Base URL" => "URL Dasar",
			"Categories" => "Kategori",
			"Category updated" => "Kategori telah diperbarui",
			"Category" => "Kategori",
			"Congratulation!" => "Selamat!",
			"Content" => "Konten",
			"Date" => "Tanggal",
			"Delete" => "Hapus",
			"Developed by" => "Dikembangkan oleh",
			"Edit Post" => "Perbarui Pos",
			"Edit" => "Ubah",
			"Enter new name for category" => "Nama kategori baru untuk",
			"Error during uploading. Try again" => "Terjadi kesalahan saatu mengunggah file. Coba lagi",
			"File is not valid. Please try again" => "File tidak valid. Coba lagi",
			"Home" => "Beranda",
			"Icon upload is OK" => "Unggah Ikon OK",
			"Image File" => "File Gambar",
			"Language" => "Bahasa",
			"Login success!" => "Login sukses!",
			"Login" => "Masuk",
			"Logo upload is OK" => "Unggah logo OK",
			"Logout" => "Keluar",
			"MORE" => "LIHAT",
			"Main Color" => "Warna Utama",
			"More in" => "Lainnya di",
			"New Post" => "Tambah Baru",
			"New category has been added" => "Kategori baru telah ditambahkan",
			"New category" => "Kategori baru",
			"New post has been published. Click" => "Pos baru berhasil diterbitkan. Klik",
			"No category has been added" => "Belum ada kategori yang sudah ditambahkan",
			"Nothing found" => "Tidak ditemukan apapun",
			"Oh no..." => "Oh tidak...",
			"One category removed" => "Satu kategori telah dihapus",
			"Picture upload is OK" => "Unggah Gambar OK",
			"Published Posts" => "Telah Terbit",
			"Recently Published" => "Baru Ditambahkan",
			"Search result for" => "Hasil pencarian kata",
			"Search" => "Cari",
			"Secondary Color" => "Warna Kedua",
			"Settings updated!" => "Pengaturan telah diperbarui!",
			"Settings" => "Pengaturan",
			"Submit" => "Tambahkan",
			"There is no category published." => "Belum ada kategori yang ditambahkan",
			"There is no post published" => "Belum ada konten",
			"Title" => "Judul",
			"Type something..." => "Ketik sesuatu...",
			"Uncategorized" => "Tanpa kategori",
			"Update" => "Perbarui",
			"Upload progress" => "Proses unggah",
			"Video File" => "File Video",
			"Video upload is OK" => "Unggah Video OK",
			"Views" => "Dilihat",
			"WELCOME!\nClick OK to start.\nIf this message appears again, please check that you have correct database connection." => "SELAMAT DATANG!\nKlik OK untuk memulai.\nJika pesan ini muncul lagi, pastikan koneksi database Anda benar.",
			"Website Icon (.ico file)" => "Ikon Situs (file .ico)",
			"Website Title" => "Judul Situs",
			"You did not submit your post correctly. Click" => "Anda tidak memposting konten dengan benar. Klik",
			"You may like:" => "Mungkin Anda tertarik:",
			"has been deleted" => "telah dihapus",
			"here" => "di sini",
			"to try again" => "untuk mencoba lagi",
			"to view it" => "untuk melihatnya",
		);
		
		return proceedTranslation($definitions, $rawword);
		
    }
	
	//proceed translation
	function proceedTranslation($definitions, $rawword){
		$translation = "[untranslated]";
		$keys = array_keys($definitions); 
		for($x = 0; $x < count($keys); $x++ ) { 
			if($keys[$x] == $rawword)
				$translation = $definitions[$keys[$x]];
		}
		return $translation;
	}
    
    $rawword;
    function uilang($txt){
        global $language;
        global $rawword;
        $rawword = $txt;
        switch($language){
            case "id" :
                return translateId();
                break;
            default :
                return $txt;
                break;
        }
    }
    
    function tt($txt1, $txt2){
        global $language;
        global $rawword;
        if($rawword == $txt1){
            return $txt2;
        }
    }
    
?>