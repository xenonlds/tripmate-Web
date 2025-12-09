<div class="stfModal_overlay" id="stfModal_overlay" role="dialog" aria-modal="true">
    <div class="stfModal_container">
        <h2 class="stfModal_title">Staff Details</h2>
        <form id="stfModal_form">

            <div class="stfModal_group">
                <label>Department</label>
                <input type="text" name="department" class="stfModal_editable" readonly>
            </div>

            <div class="stfModal_group">
                <label>Phone</label>
                <input type="tel" name="phone" class="stfModal_editable" readonly>
            </div>
            <div class="stfModal_group">
                <label>Address</label>
                <input type="text" name="address" class="stfModal_editable" readonly>
            </div>

            <div class="stfModal_group">
                <label>Status</label>
                <select name="status" id="status" disabled>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="stfModal_btnWrapper">
                <button type="button" class="stfModal_btn stfModal_edit">Edit</button>
                <button type="submit" class="stfModal_btn stfModal_save">Save</button>
                <button type="button" class="stfModal_btn stfModal_close">Close</button>
            </div>
        </form>

    </div>
</div>

<style>
    /* === Staff Modal (namespaced) === */
    .stfModal_overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(26, 28, 35, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .stfModal_overlay.stfModal_active {
        opacity: 1;
        pointer-events: all;
    }

    .stfModal_container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 450px;
    }

    .stfModal_title {
        text-align: center;
        margin-bottom: 25px;
    }

    .stfModal_group {
        margin-bottom: 15px;
    }

    .stfModal_group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .stfModal_group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .stfModal_group input[readonly] {
        background: #e9ecef;
        cursor: default;
    }

    .stfModal_btnWrapper {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .stfModal_btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        color: #fff;
    }

    .stfModal_edit {
        background: #007bff;
    }

    .stfModal_save {
        background: #28a745;
        display: none;
    }

    .stfModal_close {
        background: #dc3545;
    }
</style>