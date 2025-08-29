<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة التحكم</title>

    <!-- Tailwind (نفس حزمة ستايل لارافيل الافتراضية التي كانت عندك) -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        /* مقتبس من welcome.blade الافتراضي */
        /* ! tailwindcss v3.4.1 | https://tailwindcss.com */
        *,::after,::before{box-sizing:border-box;border-width:0;border-style:solid;border-color:#e5e7eb}::after,::before{--tw-content:''}
        html{line-height:1.5;-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;font-family:Figtree,ui-sans-serif,system-ui,sans-serif;}
        body{margin:0}
        .min-h-screen{min-height:100vh}.max-w-7xl{max-width:80rem}.w-full{width:100%}.p-6{padding:1.5rem}.py-10{padding-top:2.5rem;padding-bottom:2.5rem}
        .grid{display:grid}.gap-6{gap:1.5rem}.rounded-lg{border-radius:.5rem}.bg-white{background-color:#fff}
        .shadow{box-shadow:0 10px 15px -3px rgba(0,0,0,.1),0 4px 6px -4px rgba(0,0,0,.1)}
        .text-xl{font-size:1.25rem;line-height:1.75rem}.font-semibold{font-weight:600}.mb-4{margin-bottom:1rem}
        .text-black{color:#000}.dark\:bg-black{background-color:#000}.dark\:text-white{color:#fff}
        .container{max-width:1200px;margin-inline:auto}
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white">
    <div class="min-h-screen">
        <!-- رأس بسيط -->
        <header class="container py-10">
            <h1 class="text-xl font-semibold">لوحة التحكم</h1>
        </header>

        <main class="container p-6">
            <div class="grid gap-6">
                <!-- كارد الشارت: أكثر الشركات بيعًا -->
                <div class="rounded-lg bg-white shadow p-6 dark:bg-zinc-900">
                    <h2 class="text-xl font-semibold text-black dark:text-white mb-4">
                        أكثر الشركات المصنعة بيعًا (آخر 30 يوم)
                    </h2>
                    <canvas id="topManufacturersChart" height="140"></canvas>
                    <p id="tm-empty" class="mt-4 text-black/60 dark:text-white/70" style="display:none;">
                        لا توجد بيانات كافية لعرض الرسم.
                    </p>
                </div>

                <!-- (اختياري) ضع هنا بطاقات أخرى للـ KPIs أو الرسوم -->
            </div>
        </main>

        <footer class="container p-6 text-black dark:text-white/70">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} • PHP v{{ PHP_VERSION }}
        </footer>
    </div>

    <script>
        (async function () {
            try {
                // عدّل الرابط إذا كان لديك prefix مختلف للـ API
                const res = await fetch('/api/dashboard/top-manufacturers', {
                    headers: { 'Accept': 'application/json' }
                });

                if (!res.ok) throw new Error('HTTP ' + res.status);
                const { labels = [], values = [] } = await res.json();

                if (!labels.length || !values.length) {
                    document.getElementById('tm-empty').style.display = 'block';
                    return;
                }

                const ctx = document.getElementById('topManufacturersChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'الكمية المبيعة',
                            data: values,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y', // شارت أفقي
                        responsive: true,
                        scales: {
                            x: { beginAtZero: true, ticks: { precision: 0 } }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (c) => `الكمية: ${c.raw}`
                                }
                            }
                        }
                    }
                });
            } catch (e) {
                console.error('Top manufacturers fetch error', e);
                document.getElementById('tm-empty').style.display = 'block';
                document.getElementById('tm-empty').textContent = 'حدث خطأ أثناء تحميل البيانات.';
            }
        })();
    </script>
</body>
</html>
