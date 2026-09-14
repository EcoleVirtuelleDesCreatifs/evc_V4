/**
 * Session Time Tracking System
 * Tracks student time spent on pages, meetings, and WebTV
 */

class SessionTracker {
    constructor(config = {}) {
        this.sessionId = null;
        this.startTime = null;
        this.seanceId = config.seanceId || null;
        this.pageType = config.pageType || 'seance';
        this.heartbeatInterval = config.heartbeatInterval || 30000; // 30 seconds
        this.heartbeatTimer = null;
        this.meetingStartTime = null;
        this.meetingTimer = null;
        this.isEnabled = config.enabled !== false;
        this.apiUrl = config.apiUrl || '/evc/compte/design-graphique/session-tracking';
    }

    /**
     * Start tracking session
     */
    async start() {
        if (!this.isEnabled) return;

        try {
            const response = await fetch(`${this.apiUrl}/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    seance_id: this.seanceId,
                    page_type: this.pageType,
                    url: window.location.href,
                    referrer: document.referrer
                })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.session_id) {
                    this.sessionId = data.session_id;
                    this.startTime = new Date(data.started_at);
                    this.startHeartbeat();
                    console.log('Session tracking started:', this.sessionId);
                } else {
                    console.log('Session tracking not available:', data.warning);
                }
            }
        } catch (error) {
            console.error('Failed to start session tracking:', error);
        }
    }

    /**
     * Send heartbeat update
     */
    async sendHeartbeat() {
        if (!this.sessionId || !this.isEnabled) return;

        try {
            const response = await fetch(`${this.apiUrl}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    session_id: this.sessionId
                })
            });

            if (response.ok) {
                const data = await response.json();
                console.log('Session heartbeat:', data.duration_seconds);
            }
        } catch (error) {
            console.error('Failed to send heartbeat:', error);
        }
    }

    /**
     * Start heartbeat interval
     */
    startHeartbeat() {
        if (this.heartbeatTimer) {
            clearInterval(this.heartbeatTimer);
        }

        this.heartbeatTimer = setInterval(() => {
            this.sendHeartbeat();
        }, this.heartbeatInterval);
    }

    /**
     * Stop tracking session
     */
    async stop() {
        if (!this.sessionId || !this.isEnabled) return;

        // Stop heartbeat
        if (this.heartbeatTimer) {
            clearInterval(this.heartbeatTimer);
            this.heartbeatTimer = null;
        }

        // Stop meeting timer if running
        this.stopMeetingTimer();

        try {
            const response = await fetch(`${this.apiUrl}/end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    session_id: this.sessionId
                })
            });

            if (response.ok) {
                const data = await response.json();
                console.log('Session ended:', data.duration_seconds);
            }
        } catch (error) {
            console.error('Failed to end session:', error);
        }

        this.sessionId = null;
        this.startTime = null;
    }

    /**
     * Track meeting click and start meeting timer
     */
    trackMeetingClick(seanceId) {
        this.meetingStartTime = new Date();
        this.seanceId = seanceId;

        // Start meeting timer to track duration
        this.startMeetingTimer();

        console.log('Meeting click tracked, timer started');
    }

    /**
     * Start meeting timer
     */
    startMeetingTimer() {
        if (this.meetingTimer) {
            clearInterval(this.meetingTimer);
        }

        this.meetingTimer = setInterval(() => {
            if (this.meetingStartTime && this.seanceId) {
                const duration = Math.floor((new Date() - this.meetingStartTime) / 1000);
                this.updateMeetingClickDuration(duration);
            }
        }, 60000); // Update every minute
    }

    /**
     * Stop meeting timer
     */
    stopMeetingTimer() {
        if (this.meetingTimer) {
            clearInterval(this.meetingTimer);
            this.meetingTimer = null;
        }

        if (this.meetingStartTime) {
            const duration = Math.floor((new Date() - this.meetingStartTime) / 1000);
            this.updateMeetingClickDuration(duration);
            this.meetingStartTime = null;
        }
    }

    /**
     * Update meeting click duration
     */
    async updateMeetingClickDuration(duration) {
        if (!this.seanceId || !this.isEnabled) return;

        try {
            const response = await fetch(`${this.apiUrl}/meeting-click`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    seance_id: this.seanceId,
                    duration_seconds: duration
                })
            });

            if (response.ok) {
                console.log('Meeting duration updated:', duration);
            }
        } catch (error) {
            console.error('Failed to update meeting duration:', error);
        }
    }

    /**
     * Get session statistics
     */
    async getStats(period = 'all') {
        try {
            const response = await fetch(`${this.apiUrl}/stats?period=${period}&seance_id=${this.seanceId || ''}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (response.ok) {
                const data = await response.json();
                return data.stats;
            }
        } catch (error) {
            console.error('Failed to get stats:', error);
        }

        return null;
    }

    /**
     * Log WebTV connection
     */
    async logWebtvConnection(webtvSessionId, durationSeconds, startedAt, endedAt) {
        try {
            const response = await fetch(`${this.apiUrl}/webtv-log`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({
                    webtv_session_id: webtvSessionId,
                    duration_seconds: durationSeconds,
                    started_at: startedAt,
                    ended_at: endedAt
                })
            });

            if (response.ok) {
                const data = await response.json();
                console.log('WebTV connection logged:', data.session_id);
            }
        } catch (error) {
            console.error('Failed to log WebTV connection:', error);
        }
    }

    /**
     * Get CSRF token
     */
    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Get current session duration in seconds
     */
    getCurrentDuration() {
        if (!this.startTime) return 0;
        return Math.floor((new Date() - this.startTime) / 1000);
    }

    /**
     * Format duration as HH:MM:SS
     */
    formatDuration(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }
}

// Initialize tracker when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a seance or assiduite page
    const pageData = window.pageData || {};
    const tracker = new SessionTracker({
        seanceId: pageData.seanceId || null,
        pageType: pageData.pageType || 'seance',
        enabled: pageData.trackingEnabled !== false
    });

    // Start tracking
    tracker.start();

    // Store tracker globally for access
    window.sessionTracker = tracker;

    // Handle page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            // Page is hidden, pause tracking
            console.log('Page hidden, pausing tracking');
        } else {
            // Page is visible, resume tracking
            console.log('Page visible, resuming tracking');
            tracker.sendHeartbeat();
        }
    });

    // Handle page unload
    window.addEventListener('beforeunload', function() {
        tracker.stop();
    });

    // Track meeting clicks
    const meetButtons = document.querySelectorAll('[data-meet-link]');
    meetButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const seanceId = this.getAttribute('data-seance-id');
            if (seanceId) {
                tracker.trackMeetingClick(seanceId);
            }
        });
    });

    // Optional: Display session time on page
    const timeDisplay = document.getElementById('session-time-display');
    if (timeDisplay) {
        setInterval(() => {
            const duration = tracker.getCurrentDuration();
            timeDisplay.textContent = tracker.formatDuration(duration);
        }, 1000);
    }
});
