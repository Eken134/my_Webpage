import json
import requests
import time

def push(url: str, data: dict, user: str, password: str, verify_ssl: bool = True, timeout: int = 10):
    headers = {"Content-Type": "application/json"}
    resp = requests.post(
        url,
        data=json.dumps(data, ensure_ascii=False),
        headers={"Content-Type": "application/json"},
        auth=(user, password),
        timeout=5,
        verify=True
    )
    print(resp.status_code, resp.text)
    resp.raise_for_status()
    return resp.text


if __name__ == "__main__":
    url = "https://eelde-koivisto.se/_API/mobile/post_data.php"
    user = "testuser"
    password = "1234567890abcdef1234567890abcdef"
    data = 1234
    push(url, data, user, password)
