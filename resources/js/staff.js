
  const openBtn = document.querySelector('.btn-add');
  const overlay = document.getElementById('overlay');
  const closeBtn = document.getElementById('closeBtn');
  const cancelBtn = document.getElementById('cancelBtn');

  openBtn.addEventListener('click', () => {
    overlay.classList.add('active');
  });

  closeBtn.addEventListener('click', () => {
    overlay.classList.remove('active');
  });

  cancelBtn.addEventListener('click', () => {
    overlay.classList.remove('active');
  });

  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      overlay.classList.remove('active');
    }
  });


document.addEventListener("click", async (e) => {
  const btn = e.target.closest(".btn-action");
  if (!btn) return; 

  const userId = btn.dataset.userId;
  const currentStatus = btn.dataset.status;
  const newStatus = currentStatus === "active" ? "inactive" : "active";

  btn.textContent = newStatus === "active" ? "Deactivate" : "Activate";
  btn.dataset.status = newStatus;

  const statusBadge = btn.closest("tr").querySelector(".status-badge");
  statusBadge.textContent = newStatus;
  statusBadge.className = `status-badge ${newStatus}`;

  const payload = { status: newStatus };

  try {
    const response = await fetch(`https://tripmate-service-3.onrender.com/update/User/${userId}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (response.ok && result.status === "success") {
      console.log("Status updated successfully:", result.data);
    } else {
      console.error("Failed to update:", result.message);
      alert("Failed to update staff status.");
    }
  } catch (error) {
    console.error("⚠️ Network error while updating staff:", error);
    alert("Network error while updating staff status.");
  }
});



document.addEventListener('click', async (e) => {
  if (e.target.closest('.stfModal_openBtn')) {
    const btn = e.target.closest('.stfModal_openBtn');
    const stfModal = document.getElementById('stfModal_overlay');
    const stfModalEdit = stfModal.querySelector('.stfModal_edit');
    const stfModalSave = stfModal.querySelector('.stfModal_save');
    const stfModalClose = stfModal.querySelector('.stfModal_close');
    const stfModalInputs = stfModal.querySelectorAll('.stfModal_editable');
    const stfModalForm = document.getElementById('stfModal_form');
    let staffId = btn.dataset.id;

    try {
      const response = await fetch(`/business_owner/staff/${staffId}`);
      const data = await response.json();

      stfModal.querySelector('input[name="department"]').value = data.department ?? '';
      stfModal.querySelector('input[name="phone"]').value = data.contact_number ?? '';
      stfModal.querySelector('input[name="address"]').value = data.address ?? '';
      
     
      if (data.status) {
        stfModal.querySelector('select[name="status"]').value = data.status;
      }

      stfModal.classList.add('stfModal_active');
    } catch (error) {
      console.error('Error loading staff data:', error);
      alert('Failed to load staff details.');
    }

    stfModalClose.onclick = () => stfModal.classList.remove('stfModal_active');
    
    stfModalEdit.onclick = () => {
      stfModalInputs.forEach(i => i.removeAttribute('readonly'));
      
      stfModal.querySelector('select[name="status"]').removeAttribute('disabled');
      stfModalEdit.style.display = 'none';
      stfModalSave.style.display = 'inline-block';
    };

    stfModalForm.onsubmit = async (e) => {
      e.preventDefault();
      const formData = new FormData(stfModalForm);
      try {
        const response = await fetch(`/business_owner/staff/update/${staffId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: formData
        });

        if (response.ok) {
          alert('Staff updated successfully!');
          location.reload();
        } else {
          const err = await response.text();
          alert('Update failed: ' + err);
        }
      } catch (error) {
        alert('Error: ' + error);
      }
    };
  }
});



$(document).ready(function () {
    const loadingOverlay = $(`
      <div id="tableLoadingOverlay" 
           style="
             display:none;
             position:absolute;
             top:0;
             left:0;
             width:100%;
             height:100%;
             background:rgba(255,255,255,0.8);
             z-index:999;
             display:flex;
             justify-content:center;
             align-items:center;
             transition: opacity 0.2s ease-in-out;
             opacity:0;
           ">
        <img src="/image/Loading.gif" 
             alt="Loading..." 
             style="width:60px; height:60px;">
      </div>
    `);

    const TableBody = $("#TableBody");
    TableBody.parent().css("position", "relative");
    TableBody.after(loadingOverlay);

    $("#tableLoadingOverlay").hide().css("opacity", 0);

    function loadPage(page) {
        const overlay = $("#tableLoadingOverlay");
        overlay.show().css("opacity", 1);

        $.ajax({
            url: "/business_owner/staff",
            type: "GET",
            data: { page: page },
            success: function (response) {
                const newTbody = $(response).find("#TableBody").html();
                TableBody.fadeOut(100, function () {
                    $(this).html(newTbody).fadeIn(150);
                });
                $("#pagination").html($(response).find("#pagination").html());
                $("#staff-count").text($(response).find("#staff-count").text());
            },
            error: function () {
                alert("Try again later.");
            },
            complete: function () {
                overlay.css("opacity", 0);
                setTimeout(() => overlay.hide(), 200); 
            }
        });
    }

    $(document).on("click", ".page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        loadPage(page);
    });
});