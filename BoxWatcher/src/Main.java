import com.fazecast.jSerialComm.SerialPort;
import java.io.InputStream;
import java.sql.*;

public class Main {

    private static final String DB_URL =
            "jdbc:mysql://localhost:3306/eetmee?useSSL=false&serverTimezone=UTC";
    private static final String DB_USER = "root";
    private static final String DB_PASS = "";

    private static final String ARDUINO_PORT = "COM7";
    private static final int SERVO_DELAY_MS = 3000; // 3 seconden open

    public static void main(String[] args) {

        try {
            // 🔌 Arduino
            SerialPort arduino = SerialPort.getCommPort(ARDUINO_PORT);
            arduino.setBaudRate(9600);

            if (!arduino.openPort()) {
                System.out.println("❌ Arduino niet gevonden");
                return;
            }
            System.out.println("✅ Arduino verbonden");

            // 🛢️ Database
            Connection conn = DriverManager.getConnection(DB_URL, DB_USER, DB_PASS);
            System.out.println("✅ Database verbonden");

            while (true) {

                PreparedStatement select =
                        conn.prepareStatement(
                                "SELECT is_full, is_open, pickup_requested  FROM box WHERE id = 1"
                        );

                ResultSet rs = select.executeQuery();

                if (rs.next()) {
                    boolean isFull = rs.getBoolean("is_full");
                    boolean pickupRequested = rs.getBoolean("pickup_requested");
                    boolean isOpen = rs.getBoolean("is_open");

                    // 🍱 AFLEVEREN (box leeg)
                    if (!isFull && pickupRequested && isOpen) {
                        System.out.println("Afleveren → Servo OPEN");

                        arduino.getOutputStream().write("OPEN\n".getBytes());
                        arduino.getOutputStream().flush();
                        Thread.sleep(SERVO_DELAY_MS);

                        System.out.println("Afleveren → Servo CLOSE");
                        arduino.getOutputStream().write("CLOSE\n".getBytes());
                        arduino.getOutputStream().flush();

                        PreparedStatement update =
                                conn.prepareStatement(
                                        "UPDATE box SET is_full = true, is_open = false WHERE id = 1"
                                );
                        update.executeUpdate();
                    }

                    // 🚶 OPHALEN (knop in Symfony)
                    else if (isFull && !pickupRequested && isOpen)
                    {
                        System.out.println("Ophalen → Servo OPEN");

                        arduino.getOutputStream().write("OPEN\n".getBytes());
                        arduino.getOutputStream().flush();
                        Thread.sleep(SERVO_DELAY_MS);

                        System.out.println("Ophalen → Servo CLOSE");
                        arduino.getOutputStream().write("CLOSE\n".getBytes());
                        arduino.getOutputStream().flush();

                        PreparedStatement reset =
                                conn.prepareStatement(
                                        "UPDATE box SET is_full = false, pickup_requested = false, is_open = false WHERE id = 1"
                                );
                        reset.executeUpdate();
                    }
                    else {
                        System.out.println("Geen handeling ondernomen");
                    }
                }

                // 🔹 Temperatuur uitlezen
                InputStream arduinoIn = arduino.getInputStream();
                byte[] buffer = new byte[1024];

                while (arduinoIn.available() > 0) {
                    int len = arduinoIn.read(buffer);
                    String data = new String(buffer, 0, len).trim();

                    for (String line : data.split("\n")) {
                        line = line.trim();

                        if (line.startsWith("TEMP:")) {
                            int temp = Integer.parseInt(line.replace("TEMP:", ""));
                            System.out.println("🌡️ Temp ontvangen: " + temp);

                            PreparedStatement ps = conn.prepareStatement(
                                    "INSERT INTO temperature (value, box_id) VALUES (?, ?)"
                            );
                            //ps.setInt(1, BOX_ID);
                            ps.setInt(1, temp);
                            ps.setInt(2, 1);
                            ps.executeUpdate();

                        }
                    }
                }


                Thread.sleep(3000); // Database elke 3 sec checken
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

