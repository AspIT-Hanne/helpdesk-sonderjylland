export async function fetchFeatures() {
    try {
        const response = await fetch(window.BASE_URL + 'api/get_features.php');

        if (!response.ok) throw new Error('Kunne ikke hente features');
        return await response.json();
    } catch (error) {
        console.error("API Fejl:", error);
        return null;
    }
}