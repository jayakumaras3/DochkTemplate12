window.onload = async () => {
  const response = await fetch("certificateData.json");
  const data = await response.json();
 const bgImg = document.getElementById('header-img');
  document.getElementById("header-img").src = data.images.header;
  document.getElementById("footer-img").src = data.images.footer;

  document.getElementById("certificate-title").innerText = data.text.title;
  document.getElementById("user-name").innerText =
    localStorage.getItem("Name") || data.text.defaultName;
  document.getElementById("completion-text").innerText = data.text.completionText;
  document.getElementById("course-title").innerText = data.text.courseTitle;

  document.getElementById("date-label").innerText = data.text.dateLabel;
  document.getElementById("date-value").innerText = "";

  document.getElementById("issuer-left").innerText = data.text.signLeft.name;
  document.getElementById("issuer-right").innerText = data.text.signRight.name;
  document.getElementById("signature-left").innerText = data.text.signLeft.role;
  document.getElementById("signature-right").innerText = data.text.signRight.role;

  // Wait until bg image is fully loaded before calling print
	  bgImg.onload = function () {
		setTimeout(() => {
		  window.print();
		}, 100); // small delay to ensure rendering
	  };
};


