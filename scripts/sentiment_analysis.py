import mysql.connector
from textblob import TextBlob

DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "feedback_dashboard",
}


def classify(score: float) -> str:
    """Map a numeric polarity score (-1 to 1) to a human-readable label."""
    if score > 0.15:
        return "positive"
    elif score < -0.15:
        return "negative"
    else:
        return "neutral"


def score_pending_feedback():
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)

    cursor.execute(
        "SELECT id, comment_text FROM feedback WHERE sentiment_score IS NULL"
    )
    rows = cursor.fetchall()

    if not rows:
        print("No new feedback to score.")
        cursor.close()
        conn.close()
        return

    update_cursor = conn.cursor()

    for row in rows:
        blob = TextBlob(row["comment_text"])
        score = round(blob.sentiment.polarity, 3)
        label = classify(score)

        update_cursor.execute(
            "UPDATE feedback SET sentiment_score = %s, sentiment_label = %s WHERE id = %s",
            (score, label, row["id"])
        )
        print(f"id={row['id']} score={score:+.3f} label={label}")

    conn.commit()
    update_cursor.close()
    cursor.close()
    conn.close()
    print(f"\nScored {len(rows)} new feedback entries.")

if __name__ == "__main__":
     score_pending_feedback()