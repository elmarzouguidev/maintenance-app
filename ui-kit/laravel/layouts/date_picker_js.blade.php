<script>
    function datePicker(initialValue, minDate, maxDate, withTime, timeOnly) {
        return {
            isOpen: false,
            withTime: withTime || false,
            timeOnly: timeOnly || false,
            selectedDate: '',
            selectedHour: '08',
            selectedMinute: '00',
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            minDate: minDate || '',
            maxDate: maxDate || '',
            // Day of the visible month that holds the roving tabindex in the grid.
            focusedDay: 1,

            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre',
                'Novembre', 'Décembre'
            ],
            quickTimes: ['06:00', '08:00', '09:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],

            // What gets submitted in the form
            get finalValue() {
                if (this.timeOnly) {
                    return this.pad(this.selectedHour) + ':' + this.pad(this.selectedMinute);
                }
                if (!this.selectedDate) return '';
                if (this.withTime) {
                    return this.selectedDate + ' ' + this.pad(this.selectedHour) + ':' + this.pad(this
                        .selectedMinute);
                }
                return this.selectedDate;
            },

            // What shows in the trigger button
            get displayLabel() {
                if (this.timeOnly) {
                    return this.pad(this.selectedHour) + ':' + this.pad(this.selectedMinute);
                }
                if (!this.selectedDate) return '';
                const date = new Date(this.selectedDate + 'T00:00:00');
                const dateStr = date.toLocaleDateString('fr-FR', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                if (this.withTime) {
                    return dateStr + ' à ' + this.pad(this.selectedHour) + ':' + this.pad(this.selectedMinute);
                }
                return dateStr;
            },

            init() {
                // Keep a focusable day after the month or year changes.
                this.$watch('currentMonth', () => this.focusedDay = Math.min(this.focusedDay, this.daysInMonth()));
                this.$watch('currentYear', () => this.focusedDay = Math.min(this.focusedDay, this.daysInMonth()));

                if (!initialValue) return;

                if (this.timeOnly) {
                    // Expect "HH:MM"
                    const parts = initialValue.split(':');
                    this.selectedHour = this.pad(parts[0] || '08');
                    this.selectedMinute = this.pad(parts[1] || '00');
                    return;
                }

                if (this.withTime) {
                    // Expect "YYYY-MM-DD HH:MM"
                    const [datePart, timePart] = initialValue.split(' ');
                    this.selectedDate = datePart;
                    if (timePart) {
                        const [h, m] = timePart.split(':');
                        this.selectedHour = this.pad(h || '08');
                        this.selectedMinute = this.pad(m || '00');
                    }
                } else {
                    // Expect "YYYY-MM-DD"
                    this.selectedDate = initialValue;
                }

                if (this.selectedDate) {
                    const d = new Date(this.selectedDate + 'T00:00:00');
                    this.currentMonth = d.getMonth();
                    this.currentYear = d.getFullYear();
                }
            },

            // ── Native input (phones) ──────────────────
            // Same state as the custom picker: "Y-m-d", "Y-m-dTH:i" or "H:i" for the native control.
            get nativeValue() {
                if (this.timeOnly) return this.pad(this.selectedHour) + ':' + this.pad(this.selectedMinute);
                if (!this.selectedDate) return '';

                return this.withTime
                    ? this.selectedDate + 'T' + this.pad(this.selectedHour) + ':' + this.pad(this.selectedMinute)
                    : this.selectedDate;
            },

            setNative(value) {
                if (this.timeOnly) {
                    const [h, m] = String(value || '').split(':');
                    if (h !== undefined && m !== undefined) {
                        this.selectedHour = this.pad(h);
                        this.selectedMinute = this.pad(m);
                    }
                    return;
                }

                if (!value) {
                    this.selectedDate = '';
                    return;
                }

                const [datePart, timePart] = String(value).split('T');
                this.selectedDate = datePart;
                if (this.withTime && timePart) {
                    const [h, m] = timePart.split(':');
                    this.selectedHour = this.pad(h);
                    this.selectedMinute = this.pad(m);
                }
            },

            // ── Open / close ───────────────────────────
            toggle() {
                this.isOpen ? this.close() : this.open();
            },

            open() {
                this.isOpen = true;
                if (this.timeOnly) return;

                const today = new Date();
                if (this.selectedDate) {
                    const d = new Date(this.selectedDate + 'T00:00:00');
                    this.currentMonth = d.getMonth();
                    this.currentYear = d.getFullYear();
                    this.focusedDay = d.getDate();
                } else if (this.currentMonth === today.getMonth() && this.currentYear === today.getFullYear()) {
                    this.focusedDay = today.getDate();
                } else {
                    this.focusedDay = 1;
                }
                this.focusDay();
            },

            close() {
                this.isOpen = false;
                this.$nextTick(() => this.$refs.trigger?.focus());
            },

            focusDay() {
                this.$nextTick(() => this.$root.querySelector('[data-day="' + this.focusedDay + '"]')?.focus());
            },

            // ── Grid keyboard ──────────────────────────
            onGridKeydown(event) {
                const rtl = getComputedStyle(this.$root).direction === 'rtl';
                const weekday = (new Date(this.currentYear, this.currentMonth, this.focusedDay).getDay() + 6) % 7;
                const moves = {
                    ArrowLeft: rtl ? 1 : -1,
                    ArrowRight: rtl ? -1 : 1,
                    ArrowUp: -7,
                    ArrowDown: 7,
                    Home: -weekday,
                    End: 6 - weekday,
                };

                if (event.key in moves) {
                    event.preventDefault();
                    this.moveFocus(new Date(this.currentYear, this.currentMonth, this.focusedDay + moves[event.key]));
                } else if (event.key === 'PageUp' || event.key === 'PageDown') {
                    event.preventDefault();
                    const target = new Date(this.currentYear, this.currentMonth + (event.key === 'PageUp' ? -1 : 1), 1);
                    const lastDay = new Date(target.getFullYear(), target.getMonth() + 1, 0).getDate();
                    target.setDate(Math.min(this.focusedDay, lastDay));
                    this.moveFocus(target);
                }
            },

            moveFocus(date) {
                this.currentYear = date.getFullYear();
                this.currentMonth = date.getMonth();
                this.focusedDay = date.getDate();
                this.focusDay();
            },

            get weeks() {
                const cells = Array(this.firstDayOfMonth()).fill(null);
                for (let day = 1; day <= this.daysInMonth(); day++) cells.push(day);
                while (cells.length % 7) cells.push(null);

                return Array.from({ length: cells.length / 7 }, (_, i) => cells.slice(i * 7, i * 7 + 7));
            },

            fullDateLabel(day) {
                return new Date(this.currentYear, this.currentMonth, day).toLocaleDateString('fr-FR', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            },

            pad(n) {
                return String(parseInt(n) || 0).padStart(2, '0');
            },

            clamp(val, min, max) {
                return this.pad(Math.min(Math.max(parseInt(val) || 0, min), max));
            },

            // ── Calendar ──────────────────────────────
            prevMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else this.currentMonth--;
            },

            nextMonth() {
                if (this.currentMonth === 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                } else this.currentMonth++;
            },

            firstDayOfMonth() {
                const d = new Date(this.currentYear, this.currentMonth, 1).getDay();
                return d === 0 ? 6 : d - 1;
            },

            daysInMonth() {
                return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            },

            selectDay(day) {
                this.selectedDate = this.currentYear + '-' + this.pad(this.currentMonth + 1) + '-' + this.pad(day);
                this.focusedDay = day;
                if (!this.withTime) this.close();
            },

            selectToday() {
                const t = new Date();
                this.currentMonth = t.getMonth();
                this.currentYear = t.getFullYear();
                this.selectedDate = t.getFullYear() + '-' + this.pad(t.getMonth() + 1) + '-' + this.pad(t.getDate());
                this.focusedDay = t.getDate();
                if (!this.withTime) this.close();
            },

            isSelected(day) {
                return this.selectedDate === this.currentYear + '-' + this.pad(this.currentMonth + 1) + '-' + this.pad(
                    day);
            },

            isToday(day) {
                const t = new Date();
                return day === t.getDate() && this.currentMonth === t.getMonth() && this.currentYear === t
            .getFullYear();
            },

            isDisabled(day) {
                const d = this.currentYear + '-' + this.pad(this.currentMonth + 1) + '-' + this.pad(day);
                if (this.minDate && d < this.minDate) return true;
                if (this.maxDate && d > this.maxDate) return true;
                return false;
            },

            // ── Time ──────────────────────────────────
            incrementHour() {
                this.selectedHour = this.pad((parseInt(this.selectedHour) + 1) % 24);
            },
            decrementHour() {
                this.selectedHour = this.pad((parseInt(this.selectedHour) + 23) % 24);
            },
            incrementMinute() {
                this.selectedMinute = this.pad((parseInt(this.selectedMinute) + 1) % 60);
            },
            decrementMinute() {
                this.selectedMinute = this.pad((parseInt(this.selectedMinute) + 59) % 60);
            },

            setQuickTime(t) {
                const [h, m] = t.split(':');
                this.selectedHour = h;
                this.selectedMinute = m;
            },

            clearAll() {
                this.selectedDate = '';
                this.selectedHour = '08';
                this.selectedMinute = '00';
            },
        };
    }
</script>
