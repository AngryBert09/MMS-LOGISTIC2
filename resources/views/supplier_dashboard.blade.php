<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">📊 Supplier Performance Dashboard</h1>

    <!-- AI Insights Section -->
    <div class="mb-6 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700">
        <h2 class="text-xl font-semibold">🤖 AI Insights</h2>
        <p id="ai-insights">Fetching AI analysis...</p>
    </div>

    <!-- Supplier Performance Table -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full bg-white border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Supplier ID</th>
                    <th class="border p-2">OTD%</th>
                    <th class="border p-2">Delay (Days)</th>
                    <th class="border p-2">Defect Rate%</th>
                    <th class="border p-2">Return Rate%</th>
                    <th class="border p-2">Churn Risk%</th>
                </tr>
            </thead>
            <tbody id="supplier-data"></tbody>
        </table>
    </div>

    <!-- Churn Risk Chart -->
    <div class="mt-6">
        <canvas id="churnChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("{{ route('supplier.analysis') }}")
            .then(response => response.json())
            .then(data => {
                let suppliers = data.suppliers;
                let aiInsights = data.ai_insights;

                // Display AI Insights
                document.getElementById("ai-insights").innerText = aiInsights;

                // Populate Table
                let tableBody = document.getElementById("supplier-data");
                suppliers.forEach(supplier => {
                    let row = `<tr>
                        <td class="border p-2">${supplier.vendor_id}</td>
                        <td class="border p-2">${supplier.on_time_delivery_rate}%</td>
                        <td class="border p-2">${supplier.avg_delivery_delay} days</td>
                        <td class="border p-2">${supplier.defect_rate}%</td>
                        <td class="border p-2">${supplier.return_rate}%</td>
                        <td class="border p-2 font-bold ${supplier.churn_risk > 30 ? 'text-red-600' : 'text-green-600'}">${supplier.churn_risk}%</td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });

                // Generate Chart
                let ctx = document.getElementById("churnChart").getContext("2d");
                let labels = suppliers.map(s => s.supplier_id);
                let churnRiskData = suppliers.map(s => s.churn_risk);
                let onTimeDeliveryData = suppliers.map(s => s.on_time_delivery_rate);

                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                                label: "Churn Risk (%)",
                                data: churnRiskData,
                                backgroundColor: "rgba(255, 99, 132, 0.6)",
                            },
                            {
                                label: "On-Time Delivery (%)",
                                data: onTimeDeliveryData,
                                backgroundColor: "rgba(54, 162, 235, 0.6)",
                            }
                        ]
                    }
                });
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>
