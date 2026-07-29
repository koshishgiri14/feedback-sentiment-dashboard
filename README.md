# Customer Feedback & Sentiment Dashboard

A full-stack web app where customers submit feedback through a rated form, a Python script scores the sentiment of each comment, and a live dashboard visualizes ratings and sentiment trends in real time.

**Live tech stack:** HTML/CSS/JavaScript · PHP · MySQL · Python (TextBlob) · Chart.js

## What it does

1. A customer rates their experience (1–5 stars) and leaves a comment on a styled feedback form.
2. The form submits to a PHP endpoint, which validates the input and inserts it into a MySQL database.
3. A separate Python script scans for new, unscored feedback, runs sentiment analysis on each comment using TextBlob, and writes a polarity score and label (positive/neutral/negative) back to the database.
4. A dashboard page pulls all the data and renders it as a rating-over-time line chart, a sentiment breakdown doughnut chart, and a table of recent feedback — all built with Chart.js.

## Project structure