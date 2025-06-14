document.addEventListener("alpine:init", () => {
    Alpine.data("perizinanForm", (searchUrl, santriData = null) => ({
        searchTerm: santriData ? santriData.nama_lengkap : "",
        searchResults: [],
        showResults: false,
        isLoading: false,
        santri: {
            id: santriData ? santriData.id : null,
            nama_lengkap: santriData ? santriData.nama_lengkap : "-",
            jenis_kelamin: santriData ? santriData.jenis_kelamin : "-",
            ttl: santriData
                ? santriData.tempat_lahir
                    ? `${santriData.tempat_lahir}, ${new Date(
                          santriData.tanggal_lahir
                      ).toLocaleDateString("id-ID", {
                          day: "2-digit",
                          month: "long",
                          year: "numeric",
                      })}`
                    : "-"
                : "-",
            alamat: santriData ? santriData.alamat : "-",
            pendidikan: santriData ? santriData.pendidikan : "-",
        },

        search() {
            if (this.searchTerm.length < 2) {
                this.searchResults = [];
                return;
            }
            this.isLoading = true;
            this.showResults = true;

            fetch(`${searchUrl}?term=${this.searchTerm}`)
                .then((response) => {
                    if (!response.ok)
                        throw new Error("Network response error.");
                    return response.json();
                })
                .then((data) => {
                    this.searchResults = data;
                })
                .catch((error) => {
                    console.error("Fetch Error:", error);
                    this.searchResults = [];
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },

        selectSantri(result) {
            let tglLahirFormatted = result.tanggal_lahir
                ? new Date(result.tanggal_lahir).toLocaleDateString("id-ID", {
                      day: "2-digit",
                      month: "long",
                      year: "numeric",
                  })
                : "";

            this.santri.id = result.id || null;
            this.santri.nama_lengkap = result.nama_lengkap || "-";
            this.santri.jenis_kelamin = result.jenis_kelamin || "-";
            this.santri.ttl = result.tempat_lahir
                ? `${result.tempat_lahir}, ${tglLahirFormatted}`
                : tglLahirFormatted;
            this.santri.alamat = result.alamat || "-";
            this.santri.pendidikan = result.pendidikan || "-";

            this.searchTerm = result.nama_lengkap;
            this.showResults = false;
        },
    }));
});
