# Telegram Channel Picture Uploader

## Setup

### Obtaining API key

1) Create a bot with BotFather: https://t.me/BotFather
2) Rename `.env.example` to `.env` and put your bot's `API key` to the node `TG_BOT_API_KEY`
3) Add your newly created bot to your channel as an admin where you want to post pictures

### Obtaining chat_id

1) Go to Telegram Web, login to your account and go to the target channel
2) In the browser URL bar the link will be updated to something like `https://web.telegram.org/z/#-123456789`
3) Copy all numbers and add `-100` so it become `-100123456789` - this is you `chat_id`
4) In your `.env` file add this `chat_id` to `TG_CHAT_ID` node

## Testing

To verify your `API key` and `chat_id` are working good do following in your terminal:

`curl -X POST "https://api.telegram.org/bot<YOUR_API_KEY>/sendMessage" -d "chat_id=<YOUR_CHAT_ID>&text=Connection Test"`

Check your channel and see message appeared.

## Usage

1) Clone the repo: `git clone git@github.com:orozcodiaz/tg-picture-uploader.git`
2) `cd tg-picture-uploader/`
3) `composer install`
4) Create `.env` file: `cp .env.example .env` and add configuration from setup steps
5) Add pictures you want to upload into folder `content/`
6) Run `php post.php`
7) Check log file and your TG channel