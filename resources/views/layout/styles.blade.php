<style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
        --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }

    body {
        font-feature-settings: "cv03", "cv04", "cv11";
    }


    .dropdown-profile .dropdown-item:hover {
        background: rgb(230, 230, 230);
    }

    .image-upload-wrapper {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    /* Preview kiri */
    .image-preview-container {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
    }

    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Input kanan */
    .image-input-container {
        flex: 1;
    }

    .image-input-container input {
        width: 100%;
        padding: 10px;
        border: 2px solid #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }

    .image-input-container input:hover {
        border-color: #6366f1;
        background-color: #f8fafc;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .image-upload-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    .password-wrapper {
        position: relative;
    }

    .password-input {
        width: 100%;
        padding-right: 45px;
        /* kasih ruang untuk icon */
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toggle-password svg {
        width: 20px;
        height: 20px;
    }

    .role-permission-table label {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-right: 12px;
        font-weight: 500;
        cursor: pointer;
    }

    .role-permission-table input[type="checkbox"] {
        transform: scale(1.1);
    }


    /* Scope ke sidebar saja */
    .sidebar-menu .nav-item {
        list-style: none;
    }

    .sidebar-menu .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        color: #cbd5e1;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    /* Hover */
    .sidebar-menu .nav-link:hover {
        background-color: #1e293b;
        color: #ffffff;
    }

    /* Active */
    .sidebar-menu li.nav-item.active>a.nav-link {
        background-color: #0f172a;
        color: #ffffff;
    }

    /* Icon */
    .sidebar-menu .nav-link svg {
        stroke: currentColor;
    }

    .sidebar-menu .dropdown-item.active {
        background-color: #0f172aa1 !important;
        color: #ffffff;
        margin-left: 4px;

    }

</style>
