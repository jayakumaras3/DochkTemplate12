window.onload = async function () {
  const response = await fetch('certificateData.json');
  const data = await response.json();

  const bgImg = document.getElementById('bg-img');
  bgImg.src = data.images.bgImage;

  document.getElementById('certificate-title').innerText = data.text.title;
  document.getElementById('user-name').innerText = localStorage.getItem("Name");
  document.getElementById('completion-text').innerText = data.text.completionText;
  document.getElementById('course-title').innerText = localStorage.getItem("CourseTitle");
  document.getElementById('date-label').innerText = data.text.dateLabel;
  document.getElementById('date-value').innerText = localStorage.getItem("Date");
  document.getElementById('issuer-left').innerText = data.text.issuerLeft;
  document.getElementById('issuer-right').innerHTML = data.text.issuerRight;
  document.getElementById('signature-left').innerText = data.text.signatureLeft;
  document.getElementById('signature-right').innerText = data.text.signatureRight;

  // Wait until bg image is fully loaded before calling print
  bgImg.onload = function () {
    setTimeout(() => {
      window.print();
    }, 100); // small delay to ensure rendering
  };
};


