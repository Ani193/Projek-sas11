// data email dan password yang valid
const validAdmin = [
    { email: "Ana444@gmail.com",
      password: "1234"},
    { email: "Ani222@gmail.com",
      password: "2345"},
    { email: "Lufi888@gmail.com",
      password: "3456"},
    { email: "Elta666@gmail.com",
      password: "4567"}
  ];
  
  
  // Mengakses form
  const loginForm = document.getElementById("loginForm");
  const pesanError = document.getElementById("pesanError");
  
  // Untuk menjalankan tombol submit
  loginForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Mencegah refresh halaman
  
    // Mengambil nilai dari input
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
  
    let isValid = false;
  
    //lopping untuk mengecek apakah email dan password valid
    for (const admin of validAdmin) {
      if (admin.email === email && admin.password === password) {isValid = true;
        break; }
    }
      if (isValid) {
        alert("Login berhasil!!");
        window.location.href = "admin.html"; // untuk mengarahkan ke halaman yang di tuju
      }else{
        pesanError.textContent = "Email atau password salah.";
      }
  })