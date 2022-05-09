<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Document</title>
</head>

<body class="antialiased sans-serif">
    <?php $dates = [date('Y-m-d'), date('Y-m-d', strtotime('+2 day'))];
    echo json_encode($dates);
    echo date('Y-m-d', strtotime('Thu May 05 2022')) ?>

    <div class="flex items-center justify-center w-screen h-screen overflow-hidden bg-gray-200">
        <?php foreach ($dates as $key => $date) { ?>

            <div x-data="
            {
                showDatepicker: false,
                datepickerMin: '',
                datepickerValue: '',
                dateValue: '',
                month: '',
                year: '',
                no_of_days: [],
                blankdays: [],
                days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

                initDate() {
                    
                    if(!this.datepickerMin) {
                        let today = new Date();
                        this.month = today.getMonth();
                        this.year = today.getFullYear();

                        this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
                    } else { 
                        let minDate = new Date (this.datepickerMin);
                        this.month  = minDate.getMonth(); 
                        this.year   = minDate.getFullYear();

                        this.datepickerValue = new Date(this.year, this.month, minDate.getDate()).toDateString();
                    }
                },

                isToday(date) {
                    const today = new Date();
                    
                    const d = new Date(this.year, this.month, date);
                    //   console.log(d);
                    return today.toDateString() === d.toDateString() ? true : false;
                },

                isDisabledDate(date) {
                    
                    let theDay = '';
                    let d = new Date(this.year, this.month, date);
                    
                    if(!this.datepickerMin) {
                        theDay = new Date();
                    } else {
                        theDay = new Date(this.datepickerMin);
                    }

                    // console.log(theDay, d.getTime(), theDay.getTime(), date, d.getTime() < theDay.getTime() );

                    return d.getTime() < theDay.getTime() && theDay.toDateString() !== d.toDateString() ? true : false;
                },

                setMinDate(date) {
                    this.datepickerMin = date;
                    //  console.log(this.datepickerMin);
                },

                isSelectedDate(date) {
                    const d = new Date(this.year, this.month, date);
                
                    return this.datepickerValue === d.toDateString() ? true : false;
                },

                getDateValue(date) {
                    let selectedDate = new Date(this.year, this.month, date);
                    let minSelectedDate = new Date(this.datepickerMin);
                    if( selectedDate.getTime() >= minSelectedDate.getTime() || selectedDate.toDateString() === minSelectedDate.toDateString() ) {
                        this.datepickerValue = selectedDate.toDateString();
                        console.log(selectedDate.getMonth());

                        // this.$refs.date.value = selectedDate.getFullYear() +'-'+ ('0'+ selectedDate.getMonth()).slice(-2) +'-'+ ('0' + selectedDate.getDate()).slice(-2); // Javascript month start from 0 
                        this.dateValue = selectedDate.getFullYear() +'-'+ ('0'+ (1 + selectedDate.getMonth()) ).slice(-2) +'-'+ ('0' + selectedDate.getDate()).slice(-2); // Real month start from 1

                        // console.log(this.$refs.date.value);

                        this.showDatepicker = false;
                    }
                },

                getNoOfDays() {
                    if(this.month < 0) {
                        this.year -=  1;
                        this.month =  11;
                    }else if(this.month > 11) {
                        this.year +=  1;
                        this.month =  0;
                    }
                    // console.log(this.month);
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    // find where to start calendar day of week
                    let dayOfWeek = new Date(this.year, this.month).getDay();
                    
                    let blankdaysArray = [];
                    for ( var i=1; i <= dayOfWeek; i++) {
                        blankdaysArray.push(i);
                    }

                    let daysArray = [];
                    for ( var i=1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }

                    this.blankdays = blankdaysArray;
                    this.no_of_days = daysArray;
                }
            }
            " x-init="[setMinDate('<?= $date ?>'), initDate(), getNoOfDays()]" x-cloak>
                <div class="container px-4 py-2 mx-auto md:py-10">

                    <div class="w-64 mb-5">
                        <label for="datepicker" class="block mb-1 font-bold text-gray-700">Value <span x-text="dateValue"></span></label>
                        <label for="datepicker" class="block mb-1 font-bold text-gray-700">Select Date <span x-text="datepickerValue"></span></label>
                        <div class="relative">
                            <input type="hidden" name="date" x-ref="date">
                            <input type="text" readonly x-model="datepickerValue" @click="showDatepicker = !showDatepicker" @keydown.escape="showDatepicker = false" class="w-full py-3 pl-4 pr-10 font-medium leading-none text-gray-600 rounded-lg shadow-sm focus:outline-none focus:shadow-outline" placeholder="Select date">

                            <div class="absolute top-0 right-0 px-3 py-2">
                                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>

                            <div class="absolute top-0 left-0 p-4 mt-12 bg-white rounded-lg shadow" style="width: 17rem" x-show.transition="showDatepicker" @click.away="showDatepicker = false">

                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <button type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200" @click="year--; getNoOfDays()">
                                            <svg class="inline-flex w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div>
                                        <span x-text="year" class="ml-1 text-lg font-normal text-gray-600"></span>
                                    </div>
                                    <div>
                                        <button type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200" @click="year++; getNoOfDays()">
                                            <svg class="inline-flex w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                    </div>
                                    <div>
                                        <button type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200" @click="month--; getNoOfDays()">
                                            <svg class="inline-flex w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <button type="button" class="inline-flex p-1 transition duration-100 ease-in-out rounded-full cursor-pointer hover:bg-gray-200" @click="month++; getNoOfDays()">
                                            <svg class="inline-flex w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex flex-wrap mb-3 -mx-1">
                                    <template x-for="(day, index) in DAYS" :key="index">
                                        <div style="width: 14.26%" class="px-1">
                                            <div x-text="day" class="text-xs font-medium text-center text-gray-800"></div>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex flex-wrap -mx-1">
                                    <template x-for="blankday in blankdays">
                                        <div style="width: 14.28%" class="p-1 text-sm text-center border border-transparent"></div>
                                    </template>
                                    <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                        <div style="width: 14.28%" class="px-1 mb-1">
                                            <div @click="getDateValue(date)" x-text="date" class="text-sm leading-none leading-loose text-center transition duration-100 ease-in-out rounded-full cursor-pointer" :class="{'text-gray-200': isDisabledDate(date) == true, 'font-semibold': isToday(date) == true, ' hover:bg-blue-200': isToday(date) == false, 'font-semibold bg-red-500 text-white': isSelectedDate(date) == true, }"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        <?php } ?>


    </div>
    <script type="text/javascript">
        const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    </script>
</body>

</html>