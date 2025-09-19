#include <wiringPi.h>
#include <iostream>
#include <bitset>
#include <chrono>
#include <cpr/cpr.h>

#include <string>

using namespace std;

#define D0_PIN 25   // wPi pin for PA10
#define D1_PIN 24  // wPi pin for PA
unsigned long long cardData = 0;
int bitCount = 0;
auto lastPulse = chrono::steady_clock::now();

void handleD0() {
    if(bitCount != 0 && bitCount != 33){
        cardData = (cardData << 1); // append 0
    }

    lastPulse = chrono::steady_clock::now();
    bitCount++;

}

void handleD1() {

    if(bitCount != 0 && bitCount != 33){
        cardData = (cardData << 1) | 1; // append 1
    }

    bitCount++;
    lastPulse = chrono::steady_clock::now();
}


std::string toHexString(uint32_t value) {
    std::stringstream ss;
    ss << std::hex << std::uppercase << value;   // uppercase hex
    std::string strvalue = ss.str();

    if(ss.str().length() < 8){
       strvalue = std::string(8 - ss.str().length(), '0') + ss.str();
    }
    std::cout << strvalue << std::endl;
    return strvalue;
}




int main() {


    wiringPiSetup();
    pinMode(D0_PIN, INPUT);
    pinMode(D1_PIN, INPUT);
    pullUpDnControl(D0_PIN, PUD_UP);
    pullUpDnControl(D1_PIN, PUD_UP);

    wiringPiISR(D0_PIN, INT_EDGE_FALLING, &handleD0);
    wiringPiISR(D1_PIN, INT_EDGE_FALLING, &handleD1);

    cout << "Listening for Wiegand-36 cards..." << endl;

    while (true) {
        auto now = chrono::steady_clock::now();
        auto diff = chrono::duration_cast<chrono::milliseconds>(now - lastPulse).count();

   
	   if (bitCount == 34) {
                cout << "Card read (" << bitCount << " bits): "
                     << bitset<36>(cardData) << endl;
                cout << "Decimal: " << cardData << endl;
                auto r = cpr::Post(cpr::Url{"https://entrance.lyncxus.online/api/rfid"},
                                   cpr::Payload{{"rfid", cardData}});
                if (r.status_code == 200)
                {
                    std::cout << r.text << std::endl;
                } else {
                        std::cout << "Error: " << r.status_code << std::endl << r.text << std::endl;
                 }
            } else {
                cout << "Invalid frame (" << bitCount << " bits)" << endl;
            }

            cardData = 0;
            bitCount = 0;
        }

        delay(5);
    }

    return 0;
}
