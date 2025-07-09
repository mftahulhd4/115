import Alpine from "alpinejs";
import Collapse from "@alpinejs/collapse"; // <-- 1. TAMBAHKAN BARIS INI

window.Alpine = Alpine;

Alpine.plugin(Collapse); // <-- 2. TAMBAHKAN BARIS INI

Alpine.start();
