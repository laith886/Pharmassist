<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>لوحة التحكم</title>

  {{-- Tailwind CDN لبدء سريع --}}
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Noto Sans Arabic", "Tajawal", Arial; }
  </style>

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 text-gray-900">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
      <h1 class="text-xl font-bold">لوحة التحكم</h1>
      <div class="text-sm text-gray-500">{{ now()->format('Y-m-d') }}</div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- بطاقات سريعة --}}
    <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="p-4 bg-white rounded-xl shadow">
        <div class="text-sm text-gray-500">فواتير اليوم</div>
        <div class="mt-1 text-3xl font-bold">{{ $salesTodayCount ?? 0 }}</div>
      </div>

      <div class="p-4 bg-white rounded-xl shadow">
        <div class="text-sm text-gray-500">مبيعات اليوم</div>
        <div class="mt-1 text-3xl font-bold">
          {{ isset($salesTodayAmount) ? number_format($salesTodayAmount, 2) : '0.00' }}
        </div>
      </div>

      <div class="p-4 bg-white rounded-xl shadow">
        <div class="text-sm text-gray-500">مرتجعات اليوم (عبوات)</div>
        <div class="mt-1 text-3xl font-bold">{{ $returnsTodayQty ?? 0 }}</div>
      </div>

      <div class="p-4 bg-white rounded-xl shadow">
        <div class="text-sm text-gray-500">طلبات شراء اليوم</div>
        <div class="mt-1 text-3xl font-bold">{{ $purchasesToday ?? 0 }}</div>
      </div>
    </section>

    {{-- ترند المبيعات لآخر 7 أيام --}}
    <section class="p-4 bg-white rounded-xl shadow">
      <div class="mb-2 font-semibold">مبيعات آخر 7 أيام</div>
      <div class="h-64">
        <canvas id="salesTrend"></canvas>
      </div>
    </section>

    {{-- أفضل الشركات المصنعة --}}
    <section class="p-4 bg-white rounded-xl shadow">
      <div class="flex items-center justify-between mb-4">
        <div class="font-semibold">أفضل الشركات المصنعة</div>
        <div id="tm-total" class="text-sm text-gray-500"></div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="w-full">
          <canvas id="topManufacturersChart" height="220"></canvas>
        </div>

        <div class="w-full">
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="text-gray-500">
                <tr>
                  <th class="text-right p-2">#</th>
                  <th class="text-right p-2">الشركة</th>
                  <th class="text-right p-2">عدد الأدوية</th>
                </tr>
              </thead>
              <tbody id="topManufacturersList">
                {{-- يُعبّأ بالـ JS --}}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
      {{-- مخزون منخفض --}}
      <div class="p-4 bg-white rounded-xl shadow">
        <div class="mb-2 font-semibold">أدوية منخفضة المخزون</div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="text-gray-500">
              <tr>
                <th class="text-right p-2">الدواء</th>
                <th class="text-right p-2">المتوفر</th>
                <th class="text-right p-2">الحد الأدنى</th>
              </tr>
            </thead>
            <tbody>
              @forelse($lowStock ?? [] as $m)
                <tr class="border-t">
                  <td class="p-2">{{ $m->name }}</td>
                  <td class="p-2">{{ $m->quantity_in_stock }}</td>
                  <td class="p-2">{{ $m->minimum_quantity }}</td>
                </tr>
              @empty
                <tr><td class="p-2" colspan="3">لا يوجد عناصر منخفضة المخزون الآن.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- قرب انتهاء الصلاحية --}}
      <div class="p-4 bg-white rounded-xl shadow">
        <div class="mb-2 font-semibold">أقرب انتهاء صلاحية (30 يوم)</div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="text-gray-500">
              <tr>
                <th class="text-right p-2">الدواء</th>
                <th class="text-right p-2">تاريخ الانتهاء</th>
              </tr>
            </thead>
            <tbody>
              @forelse($expiringSoon ?? [] as $m)
                <tr class="border-t">
                  <td class="p-2">{{ $m->name }}</td>
                  <td class="p-2">{{ \Carbon\Carbon::parse($m->expiration_Date)->format('Y-m-d') }}</td>
                </tr>
              @empty
                <tr><td class="p-2" colspan="2">لا يوجد عناصر قرب انتهاء الصلاحية.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    {{-- الأكثر مبيعاً --}}
    <section class="p-4 bg-white rounded-xl shadow">
      <div class="mb-2 font-semibold">أفضل 5 أدوية مبيعًا (آخر 30 يوم)</div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="text-gray-500">
            <tr>
              <th class="text-right p-2">الدواء</th>
              <th class="text-right p-2">الكمية</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topMedicines ?? [] as $t)
              <tr class="border-t">
                <td class="p-2">{{ $t->name }}</td>
                <td class="p-2">{{ $t->qty }}</td>
              </tr>
            @empty
              <tr><td class="p-2" colspan="2">لا يوجد بيانات كافية.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <script>
    // --- مخطط مبيعات 7 أيام ---
    const trendLabels = @json($trendLabels ?? []);
    const trendValues = @json($trendValues ?? []);

    new Chart(document.getElementById('salesTrend').getContext('2d'), {
      type: 'line',
      data: {
        labels: trendLabels,
        datasets: [{
          label: 'المبيعات',
          data: trendValues,
          borderWidth: 2,
          tension: 0.25
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });

    // --- أفضل الشركات المصنعة (يجلب من الراوت) ---
    (async () => {
      try {
        const res = await fetch("{{ route('dashboard.topManufacturers') }}");
        const data = await res.json();

        const labels = (data.labels || []);
        const values = (data.values || []).map(Number);

        // إجمالي العناصر
        const total = values.reduce((a, b) => a + b, 0);
        const totalEl = document.getElementById('tm-total');
        if (totalEl) totalEl.textContent = total > 0 ? `الإجمالي: ${total}` : '';

        // جدول جانبي
        const tbody = document.getElementById('topManufacturersList');
        if (tbody) {
          tbody.innerHTML = labels.map((name, i) => `
            <tr class="border-t">
              <td class="p-2 text-gray-500">${i + 1}</td>
              <td class="p-2 font-semibold">${name}</td>
              <td class="p-2">${values[i] ?? 0}</td>
            </tr>
          `).join('');
        }

        // رسم Doughnut
        const ctx = document.getElementById('topManufacturersChart').getContext('2d');
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels,
            datasets: [{
              label: 'عدد الأدوية',
              data: values,
              backgroundColor: [
                'rgba(59,130,246,0.7)',
                'rgba(234,88,12,0.7)',
                'rgba(16,185,129,0.7)',
                'rgba(168,85,247,0.7)',
                'rgba(239,68,68,0.7)',
                'rgba(20,184,166,0.7)'
              ],
              borderWidth: 0
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: 'bottom' },
              tooltip: { rtl: true }
            },
            cutout: '60%'
          }
        });
      } catch (e) {
        console.error('Top manufacturers load error:', e);
      }
    })();
  </script>
</body>
</html>
