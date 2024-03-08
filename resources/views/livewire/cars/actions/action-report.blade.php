<div dir="ltr">
    <div class=" flex justify-center">

        <img src="https://placehold.co/100" alt="">
    </div>
    <x-section-border />

    <div class=" flex justify-between px-5">
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title_en }}
        </h2>
        <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title_ar }}
        </h2>
    </div>

    <x-section-border />


    <div class=" m-8 pt-4 px-6 border rounded-lg ">
        <div class=" flex justify-between border-b pb-4">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                Car Details
            </h2>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                بيانات السيارة
            </h2>
        </div>
        <table class="w-full text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start w-1/4">Code</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->car->code }}</td>
                <td class=" py-4 text-end w-1/4">رقم الشركة</td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Brand</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->car->brand->name }}</td>
                <td class=" py-4 text-end">النوع</td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Type</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->car->type->name }}</td>
                <td class=" py-4 text-end">الشكل</td>
            </tr>
        </table>
    </div>

    <div class=" m-8 pt-4 px-6 border rounded-lg ">
        <div class=" flex justify-between border-b pb-4">
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                Driver Details
            </h2>
            <h2 class="font-semibold text-xl flex gap-3 items-center text-gray-800 dark:text-gray-200 leading-tight">
                بيانات السائق
            </h2>
        </div>
        <table class="w-full text-base text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start w-1/4">Name</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->driver->name }}</td>
                <td class=" py-4 text-end w-1/4">الاسم</td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Date</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->date->format('d-m-Y') }}</td>
                <td class=" py-4 text-end">التاريخ</td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Time</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->time->format('H:i') }}</td>
                <td class=" py-4 text-end">الوقت</td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Kilos</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->kilos }}</td>
                <td class=" py-4 text-end">عداد الكيلو متر</td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Fuel</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->fuel }}</td>
                <td class=" py-4 text-end">البنزين</td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class=" py-4 text-start">Notes</td>
                <td class=" py-4 text-center w-1/2 font-extrabold">{{ $action->notes ?? '---' }}</td>
                <td class=" py-4 text-end">ملاحظات</td>
            </tr>
        </table>
    </div>

    <x-section-border />

</div>
{{-- style="width: 2480px; height:3508px" --}}
