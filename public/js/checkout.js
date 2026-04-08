async function applyCoupon() {
    let code = document.getElementById("coupon").value.trim().toUpperCase();
    let subtotalText = document.getElementById("subtotal").innerText;
    let subtotal = parseInt(subtotalText);

    if (code === "") {
        alert("Vui lòng nhập mã giảm giá");
        return;
    }

    try {
        let CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]') 
            ? document.querySelector('meta[name="csrf-token"]').content 
            : document.querySelector('input[name="_token"]').value;

        let response = await fetch('/api/coupons/apply', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ code: code })
        });

        let data = await response.json();

        if (data.success) {
            let discount = 0;
            let coupon = data.coupon;

            if (coupon.so_tien_giam !== null && parseFloat(coupon.so_tien_giam) > 0) {
                discount = parseFloat(coupon.so_tien_giam);
            } else if (coupon.phan_tram_giam !== null && parseFloat(coupon.phan_tram_giam) > 0) {
                discount = subtotal * (parseFloat(coupon.phan_tram_giam) / 100);
            }

            if (discount > subtotal) {
                discount = subtotal;
            }

            let total = subtotal - discount;
            let formatter = new Intl.NumberFormat('vi-VN');
            
            document.getElementById("discount").innerText = formatter.format(discount);
            document.getElementById("total-text").innerText = formatter.format(total);
            
            let hiddenInput = document.getElementById("ma_giam_gia_id");
            if (hiddenInput) {
                hiddenInput.value = coupon.id;
            }
            
            if (typeof showToast !== 'undefined') {
                showToast(data.message, "success");
            } else {
                alert(data.message);
            }
        } else {
            document.getElementById("discount").innerText = 0;
            document.getElementById("total-text").innerText = new Intl.NumberFormat('vi-VN').format(subtotal);
            let hiddenInput = document.getElementById("ma_giam_gia_id");
            if (hiddenInput) hiddenInput.value = "";
            
            if (typeof showToast !== 'undefined') {
                showToast(data.message, "error");
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error("Error applying coupon:", error);
        alert("Đã xảy ra lỗi khi kết nối tới máy chủ.");
    }
}