export function trans(langKey, replace = {}) {
    let line = window.FleetCart.langs[langKey];

    for (let key in replace) {
        line = line.replace(`:${key}`, replace[key]);
    }

    return line;
}


export function formatCurrency(amount) {
    // Xác định số chữ số thập phân dựa trên locale
    const locale = FleetCart.locale.replace("_", "-");
    const fractionDigitsMap = {
        'vi': 0, // VND, không thập phân
        'ja': 0, // JPY, không thập phân
        'en': 2, // USD, 2 chữ số thập phân
        'en': 2, // GBP, 2 chữ số thập phân
        'fr': 2, // EUR, 2 chữ số thập phân
        // Thêm các locale khác nếu cần
    };
    const fractionDigits = fractionDigitsMap[locale] ?? 2; // Mặc định 2 nếu không xác định

    return new Intl.NumberFormat(locale, {
        ...(FleetCart.locale === "ar" && {
            numberingSystem: "arab",
        }),
        style: "currency",
        currency: FleetCart.currency,
        minimumFractionDigits: fractionDigits,
        maximumFractionDigits: fractionDigits,
    }).format(amount);
}
