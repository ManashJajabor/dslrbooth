import pyautogui
import time
print("Hello from Python!")
# Delay for user to switch to desired application window
switch_delay = 5

# Bring the background application to the foreground
def bring_to_foreground():
    # Simulate Alt + Tab keypress to switch windows
    pyautogui.hotkey('alt', 'tab')
    time.sleep(switch_delay)  # Wait for switch to complete

# Move the current application to the background
def move_to_background():
    # Minimize the current window
    pyautogui.hotkey('win', 'd')

# Main function
def main():
    bring_to_foreground()  # Bring background application to foreground
    #move_to_background()  # Move current application to background

# Run the main function
if __name__ == "__main__":
    main()
