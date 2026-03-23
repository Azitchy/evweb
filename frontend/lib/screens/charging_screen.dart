import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/charging_provider.dart';
import '../providers/wallet_provider.dart';

class ChargingScreen extends StatefulWidget {
  const ChargingScreen({super.key});

  @override
  State<ChargingScreen> createState() => _ChargingScreenState();
}

class _ChargingScreenState extends State<ChargingScreen> {
  final _percentageController = TextEditingController();

  @override
  void dispose() {
    _percentageController.dispose();
    super.dispose();
  }

  Future<void> _startCharging() async {
    final percentage = double.tryParse(_percentageController.text);
    if (percentage == null || percentage < 0 || percentage > 100) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Enter a valid percentage (0-100)')),
      );
      return;
    }

    final charging = context.read<ChargingProvider>();
    final success = await charging.startCharging(percentage);

    if (!mounted) return;

    if (success) {
      _percentageController.clear();
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Charging started!')));
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(charging.error ?? 'Failed to start')),
      );
    }
  }

  Future<void> _stopCharging() async {
    final percentage = double.tryParse(_percentageController.text);
    if (percentage == null || percentage < 0 || percentage > 100) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Enter the current battery percentage')),
      );
      return;
    }

    final charging = context.read<ChargingProvider>();
    final success = await charging.stopCharging(percentage);

    if (!mounted) return;

    if (success) {
      _percentageController.clear();
      context.read<WalletProvider>().fetchBalance();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Charging completed! Amount deducted from wallet.'),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(charging.error ?? 'Failed to stop')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final charging = context.watch<ChargingProvider>();
    final wallet = context.watch<WalletProvider>();

    return Scaffold(
      appBar: AppBar(title: const Text('Charging')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            // Charging Status Indicator
            Container(
              width: 180,
              height: 180,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: charging.isCharging
                    ? Colors.orange.withValues(alpha: 0.15)
                    : Colors.green.withValues(alpha: 0.15),
                border: Border.all(
                  color: charging.isCharging ? Colors.orange : Colors.green,
                  width: 4,
                ),
              ),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    charging.isCharging ? Icons.bolt : Icons.ev_station,
                    size: 48,
                    color: charging.isCharging ? Colors.orange : Colors.green,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    charging.isCharging ? 'Charging...' : 'Ready',
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: charging.isCharging ? Colors.orange : Colors.green,
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Wallet Balance Display
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Wallet Balance'),
                    Text(
                      '₹ ${wallet.balance.toStringAsFixed(2)}',
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 18,
                      ),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Active Session Info
            if (charging.isCharging) ...[
              Card(
                color: Colors.orange.withValues(alpha: 0.15),
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Active Session',
                        style: TextStyle(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 8),
                      Text(
                        'Started at: ${charging.activeSession!.startPercentage.toStringAsFixed(0)}%',
                      ),
                      Text(
                        'Rate: ₹ ${charging.activeSession!.pricePerPercentage.toStringAsFixed(2)} per %',
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
            ],

            // Percentage Input
            TextFormField(
              controller: _percentageController,
              keyboardType: const TextInputType.numberWithOptions(
                decimal: true,
              ),
              decoration: InputDecoration(
                labelText: charging.isCharging
                    ? 'Current Battery Percentage'
                    : 'Starting Battery Percentage',
                prefixIcon: const Icon(Icons.battery_charging_full),
                suffixText: '%',
              ),
            ),
            const SizedBox(height: 24),

            // Action Button
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: charging.isLoading
                    ? null
                    : (charging.isCharging ? _stopCharging : _startCharging),
                icon: Icon(charging.isCharging ? Icons.stop : Icons.play_arrow),
                label: charging.isLoading
                    ? const SizedBox(
                        height: 20,
                        width: 20,
                        child: CircularProgressIndicator(strokeWidth: 2),
                      )
                    : Text(
                        charging.isCharging
                            ? 'Stop Charging'
                            : 'Start Charging',
                      ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: charging.isCharging
                      ? Colors.red
                      : Theme.of(context).colorScheme.primary,
                  foregroundColor: Colors.white,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
