import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../providers/wallet_provider.dart';
import '../models/models.dart';

class WalletScreen extends StatefulWidget {
  const WalletScreen({super.key});

  @override
  State<WalletScreen> createState() => _WalletScreenState();
}

class _WalletScreenState extends State<WalletScreen> {
  @override
  void initState() {
    super.initState();
    context.read<WalletProvider>().fetchBalance();
    context.read<WalletProvider>().fetchTransactions();
  }

  void _showAddMoneyDialog() {
    final controller = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Add Money'),
        content: TextField(
          controller: controller,
          keyboardType: const TextInputType.numberWithOptions(decimal: true),
          decoration: const InputDecoration(
            labelText: 'Amount (₹)',
            prefixText: '₹ ',
          ),
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx),
            child: const Text('Cancel'),
          ),
          ElevatedButton(
            onPressed: () async {
              final amount = double.tryParse(controller.text);
              if (amount == null || amount <= 0) {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Enter a valid amount')),
                );
                return;
              }
              Navigator.pop(ctx);
              final wallet = context.read<WalletProvider>();
              final success = await wallet.addMoney(amount);
              if (!mounted) return;
              if (success) {
                wallet.fetchTransactions();
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Money added successfully!')),
                );
              } else {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Text(wallet.error ?? 'Failed to add money'),
                  ),
                );
              }
            },
            child: const Text('Add'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final wallet = context.watch<WalletProvider>();

    return Scaffold(
      appBar: AppBar(title: const Text('Wallet')),
      body: RefreshIndicator(
        onRefresh: () async {
          await wallet.fetchBalance();
          await wallet.fetchTransactions();
        },
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            // Balance Card
            Card(
              color: Theme.of(context).colorScheme.primaryContainer,
              child: Padding(
                padding: const EdgeInsets.all(24),
                child: Column(
                  children: [
                    const Text('Available Balance'),
                    const SizedBox(height: 8),
                    Text(
                      '₹ ${wallet.balance.toStringAsFixed(2)}',
                      style: Theme.of(context).textTheme.headlineLarge
                          ?.copyWith(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 16),
                    ElevatedButton.icon(
                      onPressed: _showAddMoneyDialog,
                      icon: const Icon(Icons.add),
                      label: const Text('Add Money'),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),

            // Transactions
            Text(
              'Recent Transactions',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 12),

            if (wallet.isLoading)
              const Center(child: CircularProgressIndicator())
            else if (wallet.transactions.isEmpty)
              const Center(
                child: Padding(
                  padding: EdgeInsets.all(32),
                  child: Text('No transactions yet'),
                ),
              )
            else
              ...wallet.transactions.map(
                (t) => _TransactionTile(transaction: t),
              ),
          ],
        ),
      ),
    );
  }
}

class _TransactionTile extends StatelessWidget {
  final Transaction transaction;

  const _TransactionTile({required this.transaction});

  @override
  Widget build(BuildContext context) {
    final isCredit = transaction.type == 'credit';
    final dateStr = DateFormat(
      'dd MMM yyyy, hh:mm a',
    ).format(transaction.createdAt);

    return Card(
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: isCredit ? Colors.green.shade50 : Colors.red.shade50,
          child: Icon(
            isCredit ? Icons.arrow_downward : Icons.arrow_upward,
            color: isCredit ? Colors.green : Colors.red,
          ),
        ),
        title: Text(transaction.description),
        subtitle: Text(dateStr),
        trailing: Text(
          '${isCredit ? '+' : '-'} ₹${transaction.amount.toStringAsFixed(2)}',
          style: TextStyle(
            fontWeight: FontWeight.bold,
            color: isCredit ? Colors.green : Colors.red,
          ),
        ),
      ),
    );
  }
}
