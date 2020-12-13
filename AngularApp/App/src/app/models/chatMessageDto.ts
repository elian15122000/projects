export class ChatMessageDto {
    user: string;
    message: string;

    consructor(user: string, message: string) {
        this.user = user;
        this.message = message;
    }
}