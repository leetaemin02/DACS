function applyCoupon() {
    let code = document.getElementById("coupon").value.trim().toUpperCase();
    let subtotalText = document.getElementById("subtotal").innerText;
    let subtotal = parseInt(subtotalText);

    let discount = 0;

    if (code === "") {
        alert("Vui lòng nhập mã giảm giá");
        return;
    }

    if (code === "SALE10") {
        discount = subtotal * 0.1;
        showToast ? showToast("Áp dụng mã thành công! Bạn được giảm 10%", "success") : alert("Áp dụng mã thành công!");
    } else {
        showToast ? showToast("Mã giảm giá không hợp lệ", "error") : alert("Mã giảm giá không hợp lệ");
        return;
    }

    let total = subtotal - discount;

    let formatter = new Intl.NumberFormat('vi-VN');
    
    document.getElementById("discount").innerText = formatter.format(discount);
    document.getElementById("total-text").innerText = formatter.format(total);
}